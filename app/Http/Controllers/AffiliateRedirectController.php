<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AffiliateClickLog;
use App\Models\Hotel;
use App\Models\Restaurant;

class AffiliateRedirectController extends Controller
{
    /**
     * Configuration-driven entity map.
     * Add new entity types here without modifying controller logic.
     */
    protected array $entityMap = [
        'hotel'      => Hotel::class,
        'restaurant' => Restaurant::class,
        'promotion'  => \App\Models\AffiliatePromotion::class,
        // 'attraction' => Attraction::class,
        // 'event'      => Event::class,
    ];

    /**
     * Track click and redirect to affiliate URL.
     */
    public function redirect(Request $request, string $type, int $id)
    {
        // 1. Validate entity type
        if (!array_key_exists($type, $this->entityMap)) {
            abort(404);
        }

        // 2. Find the entity
        $modelClass = $this->entityMap[$type];
        $entity = $modelClass::find($id);
        if (!$entity) {
            abort(404);
        }

        // 3. Find affiliate link via relationship
        $affiliateLink = $entity->affiliateLink;
        if (!$affiliateLink || !$affiliateLink->is_active) {
            abort(404);
        }

        // 4. Resolve visitor UUID (cookie-based, no login required)
        $visitorId = $request->cookie('visitor_uuid') ?? (string) Str::uuid();

        // 5. Resolve real visitor IP (handle reverse proxies like ngrok / load balancers)
        $visitorIp = null;
        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            $visitorIp = trim($ips[0]);
        } elseif ($request->header('X-Real-IP')) {
            $visitorIp = $request->header('X-Real-IP');
        } elseif ($request->server('HTTP_X_FORWARDED_FOR')) {
            $ips = explode(',', $request->server('HTTP_X_FORWARDED_FOR'));
            $visitorIp = trim($ips[0]);
        }
        
        if (empty($visitorIp)) {
            $visitorIp = $request->ip();
        }

        // 6. Geolocate visitor IP
        $geo = \App\Services\GeoIPResolver::resolve($visitorIp);

        // 7. Log the click
        AffiliateClickLog::create([
            'affiliate_link_id' => $affiliateLink->id,
            'entity_type'       => $type,
            'entity_id'         => $id,
            'visitor_id'        => $visitorId,
            'ip_address'        => $visitorIp,
            'user_agent'        => $request->userAgent(),
            'referer'           => $request->header('referer'),
            'country_code'      => $geo['country_code'],
            'country_name'      => $geo['country_name'],
            'state'             => $geo['state'],
            'city'              => $geo['city'],
            'clicked_at'        => now(),
        ]);

        // 6. Increment click counter atomically
        $affiliateLink->increment('total_clicks');

        // 7. Set visitor cookie and redirect
        return redirect()->away($affiliateLink->link)
            ->cookie('visitor_uuid', $visitorId, 60 * 24 * 365); // 365 days
    }
}
