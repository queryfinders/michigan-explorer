<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateLink;
use App\Models\AffiliateClickLog;
use App\Models\Hotel;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\Sortable;

class AffiliateLinkController extends Controller
{
    use Sortable, \App\Traits\Exportable;
    public function index(Request $request)
    {
        $query = AffiliateLink::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('link', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $query = $this->applySorting($query, ['id', 'name', 'provider', 'link', 'is_active', 'created_at'], 'created_at', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'affiliate_links_export', function ($link) {
                return [
                    'ID' => $link->id,
                    'Name' => $link->name,
                    'Provider' => $link->provider,
                    'Link' => $link->link,
                    'Status' => $link->is_active ? 'Active' : 'Inactive',
                    'Created At' => $link->created_at ? $link->created_at->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $affiliateLinks = $query->paginate(15);
        
        if ($request->ajax()) {
            return view('new_content.admin.affiliate_links._table', compact('affiliateLinks'))->render();
        }
        
        return view('new_content.admin.affiliate_links.index', compact('affiliateLinks'));
    }

    public function export($id, $format)
    {
        $affiliateLink = AffiliateLink::findOrFail($id);
        $logs = AffiliateClickLog::where('affiliate_link_id', $id)->orderByDesc('clicked_at')->get();

        if ($format === 'csv') {
            return $this->exportCsv($affiliateLink, $logs);
        }

        if ($format === 'excel') {
            return $this->exportExcel($affiliateLink, $logs);
        }

        abort(404);
    }

    protected function exportCsv($affiliateLink, $logs)
    {
        $fileName = 'click_logs_' . Str::slug($affiliateLink->name) . '_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Log ID', 'Entity Type', 'Entity ID', 'Visitor ID', 'IP Address', 'Browser', 'Platform', 'Device', 'Referrer', 'Country Code', 'Country Name', 'State', 'City', 'Clicked At']);

            foreach ($logs as $log) {
                $parsedAgent = $this->parseUserAgent($log->user_agent);
                $parsedRef = \App\Services\ReferrerAnalyzer::parse($log->referer);
                
                fputcsv($file, [
                    $log->id,
                    $log->entity_type,
                    $log->entity_id,
                    $log->visitor_id,
                    $log->ip_address,
                    $parsedAgent['browser'],
                    $parsedAgent['platform'],
                    $parsedAgent['device'],
                    $parsedRef,
                    $log->country_code,
                    $log->country_name,
                    $log->state,
                    $log->city,
                    $log->clicked_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportExcel($affiliateLink, $logs)
    {
        $fileName = 'click_logs_' . Str::slug($affiliateLink->name) . '_' . date('Y-m-d') . '.xls';
        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($logs) {
            $file = fopen('php://output', 'w');
            
            // TSV row header
            fwrite($file, "Log ID\tEntity Type\tEntity ID\tVisitor ID\tIP Address\tBrowser\tPlatform\tDevice\tReferrer\tCountry Code\tCountry Name\tState\tCity\tClicked At\n");

            foreach ($logs as $log) {
                $parsedAgent = $this->parseUserAgent($log->user_agent);
                $parsedRef = \App\Services\ReferrerAnalyzer::parse($log->referer);
                
                fwrite($file, implode("\t", [
                    $log->id,
                    $log->entity_type,
                    $log->entity_id,
                    $log->visitor_id,
                    $log->ip_address,
                    $parsedAgent['browser'],
                    $parsedAgent['platform'],
                    $parsedAgent['device'],
                    $parsedRef,
                    $log->country_code,
                    $log->country_name,
                    $log->state,
                    $log->city,
                    $log->clicked_at
                ]) . "\n");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('new_content.admin.affiliate_links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'provider'    => 'nullable|string|max:255',
            'link'        => 'required|url',
            'description' => 'nullable|string',
            'is_active'   => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $affiliateLink = AffiliateLink::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'affiliateLink' => $affiliateLink]);
        }

        return redirect()->route('affiliate-links.index')->with('success', 'Affiliate Link created successfully.');
    }

    public function show(Request $request, AffiliateLink $affiliateLink)
    {
        // 1. Core Summary Metrics
        $totalClicks = $affiliateLink->total_clicks;
        $uniqueVisitors = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->distinct('visitor_id')
            ->count('visitor_id');

        $clicksToday = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->whereDate('clicked_at', Carbon::today())
            ->count();

        $uniqueToday = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->whereDate('clicked_at', Carbon::today())
            ->distinct('visitor_id')
            ->count('visitor_id');

        $clicksThisWeek = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->where('clicked_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        $clicksThisMonth = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->where('clicked_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $lastClickLog = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->orderByDesc('clicked_at')
            ->first();

        // 2. Daily Trend Chart Data (Last 30 Days)
        $chartDays = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDays[] = $date->format('M d');
            $chartData[] = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
                ->whereDate('clicked_at', $date)
                ->count();
        }

        // 3. Distribution Metrics (Polymorphic entity counts)
        $distribution = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->select('entity_type', DB::raw('count(*) as count'))
            ->groupBy('entity_type')
            ->get()
            ->pluck('count', 'entity_type')
            ->toArray();

        $totalDistClicks = array_sum($distribution);
        $distributionPercent = [];
        foreach ($distribution as $type => $count) {
            $distributionPercent[ucfirst(Str::plural($type))] = $totalDistClicks > 0 ? round(($count / $totalDistClicks) * 100, 1) : 0;
        }

        // 4. Top Associated Entities
        $topHotels = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->where('entity_type', 'hotel')
            ->select('entity_id', DB::raw('count(*) as clicks'), DB::raw('max(clicked_at) as last_click'))
            ->groupBy('entity_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();
        $hotelIds = $topHotels->pluck('entity_id');
        $hotelsMap = Hotel::whereIn('id', $hotelIds)->get()->keyBy('id');
        foreach($topHotels as $item) {
            $item->hotel = $hotelsMap->get($item->entity_id);
        }
        $topHotels = $topHotels->filter(fn($item) => !is_null($item->hotel));

        $topRestaurants = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->where('entity_type', 'restaurant')
            ->select('entity_id', DB::raw('count(*) as clicks'), DB::raw('max(clicked_at) as last_click'))
            ->groupBy('entity_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();
        $restaurantIds = $topRestaurants->pluck('entity_id');
        $restaurantsMap = Restaurant::whereIn('id', $restaurantIds)->get()->keyBy('id');
        foreach($topRestaurants as $item) {
            $item->restaurant = $restaurantsMap->get($item->entity_id);
        }
        $topRestaurants = $topRestaurants->filter(fn($item) => !is_null($item->restaurant));

        // 5. Recent Click Activity & Parsed Metrics
        $logsQuery = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->orderByDesc('clicked_at');

        // Paginate logs
        $clickLogs = (clone $logsQuery)->paginate(15, ['*'], 'logs_page');

        // For recent activity (25 records) and overall breakdown
        $recentLogs = (clone $logsQuery)->limit(25)->get();

        // Location Analytics
        $topCountries = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->select('country_name', 'country_code', DB::raw('count(*) as clicks'), DB::raw('count(distinct visitor_id) as visitors'))
            ->groupBy('country_name', 'country_code')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();

        $topStates = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->select('state', DB::raw('count(*) as clicks'), DB::raw('count(distinct visitor_id) as visitors'))
            ->groupBy('state')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();

        $topCities = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)
            ->select('city', DB::raw('count(*) as clicks'), DB::raw('count(distinct visitor_id) as visitors'))
            ->groupBy('city')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();

        // 6. Referrer Analytics (All-time parsed metrics)
        $allRefererLogs = AffiliateClickLog::where('affiliate_link_id', $affiliateLink->id)->get(['referer']);
        $referrers = \App\Services\ReferrerAnalyzer::analyze($allRefererLogs);

        // Parse logs to add UI helpers
        $parsedLogs = [];
        $browsers = ['Chrome' => 0, 'Safari' => 0, 'Firefox' => 0, 'Edge' => 0, 'Other' => 0];
        $devices = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];

        // Preload entities for the recent logs to avoid N+1
        $rHotelIds = $recentLogs->where('entity_type', 'hotel')->pluck('entity_id');
        $rRestaurantIds = $recentLogs->where('entity_type', 'restaurant')->pluck('entity_id');
        $rHotels = Hotel::whereIn('id', $rHotelIds)->get()->keyBy('id');
        $rRestaurants = Restaurant::whereIn('id', $rRestaurantIds)->get()->keyBy('id');

        foreach ($recentLogs as $log) {
            $parsedAgent = $this->parseUserAgent($log->user_agent);
            $parsedRef = \App\Services\ReferrerAnalyzer::parse($log->referer);

            $browsers[$parsedAgent['browser']] = ($browsers[$parsedAgent['browser']] ?? 0) + 1;
            $devices[$parsedAgent['device']] = ($devices[$parsedAgent['device']] ?? 0) + 1;

            $entityName = 'Unknown Entity';
            $entityEditUrl = '#';
            if ($log->entity_type === 'hotel') {
                $h = $rHotels->get($log->entity_id);
                $entityName = $h ? $h->name : 'Deleted Hotel';
                if ($h) $entityEditUrl = route('hotels.edit', $h->id);
            } elseif ($log->entity_type === 'restaurant') {
                $r = $rRestaurants->get($log->entity_id);
                $entityName = $r ? $r->name : 'Deleted Restaurant';
                if ($r) $entityEditUrl = route('restaurants.edit', $r->id);
            }

            $parsedLogs[] = [
                'clicked_at' => $log->clicked_at,
                'entity_name' => $entityName,
                'entity_url' => $entityEditUrl,
                'entity_type' => $log->entity_type,
                'visitor_id' => substr($log->visitor_id, 0, 8) . '...',
                'browser' => $parsedAgent['browser'],
                'platform' => $parsedAgent['platform'],
                'device' => $parsedAgent['device'],
                'referrer' => $parsedRef,
                'country_code' => $log->country_code,
                'country_name' => $log->country_name ?: 'Local Network / Testing',
                'state' => $log->state ?: 'Testing Environment',
                'city' => $log->city ?: 'Localhost',
                'ip_address' => $this->maskIp($log->ip_address)
            ];
        }

        // Percentage helpers
        $totalParsed = count($recentLogs) ?: 1;
        $browserPercentages = [];
        foreach($browsers as $b => $c) {
            $browserPercentages[$b] = round(($c / $totalParsed) * 100, 1);
        }
        $devicePercentages = [];
        foreach($devices as $d => $c) {
            $devicePercentages[$d] = round(($c / $totalParsed) * 100, 1);
        }

        // Preload for paginated logs
        $pHotelIds = $clickLogs->where('entity_type', 'hotel')->pluck('entity_id');
        $pRestaurantIds = $clickLogs->where('entity_type', 'restaurant')->pluck('entity_id');
        $pHotels = Hotel::whereIn('id', $pHotelIds)->get()->keyBy('id');
        $pRestaurants = Restaurant::whereIn('id', $pRestaurantIds)->get()->keyBy('id');

        foreach ($clickLogs as $log) {
            $parsedAgent = $this->parseUserAgent($log->user_agent);
            $log->parsed_browser = $parsedAgent['browser'];
            $log->parsed_platform = $parsedAgent['platform'];
            $log->parsed_device = $parsedAgent['device'];
            $log->parsed_referrer = $this->parseReferrer($log->referer);
            $log->masked_ip = $this->maskIp($log->ip_address);
            
            if ($log->entity_type === 'hotel') {
                $h = $pHotels->get($log->entity_id);
                $log->entity_name = $h ? $h->name : 'Deleted Hotel';
            } elseif ($log->entity_type === 'restaurant') {
                $r = $pRestaurants->get($log->entity_id);
                $log->entity_name = $r ? $r->name : 'Deleted Restaurant';
            } else {
                $log->entity_name = ucfirst($log->entity_type) . ' #' . $log->entity_id;
            }
        }

        return view('new_content.admin.affiliate_links.show', compact(
            'affiliateLink',
            'totalClicks',
            'uniqueVisitors',
            'clicksToday',
            'uniqueToday',
            'clicksThisWeek',
            'clicksThisMonth',
            'lastClickLog',
            'chartDays',
            'chartData',
            'distributionPercent',
            'topHotels',
            'topRestaurants',
            'parsedLogs',
            'browsers',
            'browserPercentages',
            'devices',
            'devicePercentages',
            'referrers',
            'topCountries',
            'topStates',
            'topCities',
            'clickLogs'
        ));
    }

    private function parseUserAgent($userAgent)
    {
        if (empty($userAgent)) {
            return ['browser' => 'Other', 'platform' => 'Other', 'device' => 'Desktop'];
        }
        
        $browser = 'Other';
        $platform = 'Other';
        $device = 'Desktop';

        // Device
        if (preg_match('/(ipad|tablet|playbook|silk)|(android(?!.*mobile))/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/(mobi|ipod|phone|blackberry|opera mini|fennec|minimo|symbian|psp|nintendo ds)/i', $userAgent)) {
            $device = 'Mobile';
        }

        // Platform / OS
        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        // Browser
        if (preg_match('/edge|edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        return compact('browser', 'platform', 'device');
    }

    private function parseReferrer($referer)
    {
        if (empty($referer)) {
            return 'Direct';
        }
        $host = parse_url($referer, PHP_URL_HOST);
        if (empty($host)) {
            return 'Direct';
        }
        
        if (stripos($host, 'google.com') !== false) return 'Google';
        if (stripos($host, 'facebook.com') !== false || stripos($host, 'fb.me') !== false) return 'Facebook';
        if (stripos($host, 'instagram.com') !== false) return 'Instagram';
        if (stripos($host, 'whatsapp.com') !== false || stripos($host, 'wa.me') !== false) return 'WhatsApp';
        if (stripos($host, 'linkedin.com') !== false || stripos($host, 'lnkd.in') !== false) return 'LinkedIn';
        if (stripos($host, 't.co') !== false || stripos($host, 'twitter.com') !== false) return 'Twitter';
        
        return $host;
    }

    private function maskIp($ip)
    {
        if (empty($ip)) return 'xxx.xxx.xxx.xxx';
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            return $parts[0] . '.xxx.xxx.xxx';
        }
        return 'xxx.xxx.xxx.xxx';
    }

    public function edit(AffiliateLink $affiliateLink)
    {
        return view('new_content.admin.affiliate_links.edit', compact('affiliateLink'));
    }

    public function update(Request $request, AffiliateLink $affiliateLink)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'provider'    => 'nullable|string|max:255',
            'link'        => 'required|url',
            'description' => 'nullable|string',
            'is_active'   => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $affiliateLink->update($data);

        return redirect()->route('affiliate-links.index')->with('success', 'Affiliate Link updated successfully.');
    }

    public function destroy(AffiliateLink $affiliateLink)
    {
        $affiliateLink->delete();
        return redirect()->route('affiliate-links.index')->with('success', 'Affiliate Link deleted successfully.');
    }

    public function changeStatus($id, $status)
    {
        $affiliateLink = AffiliateLink::findOrFail($id);
        $affiliateLink->is_active = $status;
        $affiliateLink->save();

        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully.', 
            'status'  => $affiliateLink->is_active
        ]);
    }
}
