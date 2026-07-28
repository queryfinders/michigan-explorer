@extends('layouts/layoutMaster')

@section('title', 'Affiliate Link Analytics')

@section('content')
@php
if (!function_exists('getFlagEmoji')) {
    function getFlagEmoji($countryCode) {
        if (empty($countryCode)) return '🏳️';
        $code = strtoupper($countryCode);
        if ($code === 'US') return '🇺🇸';
        if ($code === 'IN') return '🇮🇳';
        if ($code === 'CA') return '🇨🇦';
        if ($code === 'GB') return '🇬🇧';
        if ($code === 'AU') return '🇦🇺';
        if ($code === 'DE') return '🇩🇪';
        if ($code === 'FR') return '🇫🇷';
        if ($code === 'JP') return '🇯🇵';
        
        // Convert ISO 3166-1 alpha-2 code to regional indicator flags
        try {
            $flag = '';
            for ($i = 0; $i < strlen($code); $i++) {
                $flag .= mb_convert_encoding('&#' . (127397 + ord($code[$i])) . ';', 'UTF-8', 'HTML-ENTITIES');
            }
            return $flag;
        } catch (\Exception $e) {
            return '🏳️';
        }
    }
}
@endphp
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('affiliate-links.index') }}">Affiliate Links</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $affiliateLink->name }}</li>
  </ol>
</nav>

<!-- Rich Header Section -->
<div class="card mb-4 border-0 shadow-sm">
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-md-6 mb-3 mb-md-0">
        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
          <h4 class="mb-0 fw-bold me-2">{{ $affiliateLink->name }}</h4>
          @if($affiliateLink->provider)
            <span class="badge bg-label-primary px-3 py-2 fs-7">{{ $affiliateLink->provider }}</span>
          @endif
          @if($affiliateLink->is_active)
            <span class="badge bg-success px-3 py-2 fs-7"><i class="fa fa-circle me-1 small"></i> Active</span>
          @else
            <span class="badge bg-danger px-3 py-2 fs-7"><i class="fa fa-circle me-1 small"></i> Inactive</span>
          @endif
        </div>
        <div class="d-flex align-items-center mb-3">
          <span class="text-muted small me-2 text-truncate" style="max-width: 350px;" id="destUrl">{{ $affiliateLink->link }}</span>
          <button class="btn btn-sm btn-icon btn-outline-secondary border-0 p-0" onclick="copyUrl('{{ $affiliateLink->link }}', this)" title="Copy URL">
            <i class="fa-regular fa-copy"></i>
          </button>
        </div>
        <div class="d-flex flex-wrap gap-3 text-muted small">
          <div><i class="fa-regular fa-calendar me-1"></i> Created: {{ $affiliateLink->created_at->format('M d, Y h:i A') }}</div>
          <div><i class="fa-regular fa-clock me-1"></i> Updated: {{ $affiliateLink->updated_at->format('M d, Y h:i A') }}</div>
        </div>
      </div>
      <div class="col-md-6 text-md-end d-flex gap-2 justify-content-md-end justify-content-start flex-wrap align-items-center">
        <a href="{{ route('admin.affiliate-links.export', [$affiliateLink->id, 'csv']) }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-csv me-1"></i> Export CSV</a>
        <a href="{{ route('admin.affiliate-links.export', [$affiliateLink->id, 'excel']) }}" class="btn btn-outline-secondary"><i class="fa-solid fa-file-excel me-1"></i> Export Excel</a>
        <a href="{{ route('affiliate-links.edit', $affiliateLink->id) }}" class="btn btn-primary"><i class="fa-regular fa-pen-to-square me-1"></i> Edit Link</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Main Content Area -->
  <div class="col-lg-9 col-md-12">
    <!-- Premium Summary Cards -->
    <div class="row mb-4">
      <!-- Total Clicks -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #7367f0 !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">Total {{ $totalClicks == 1 ? 'Click' : 'Clicks' }}</span>
              <h3 class="fw-bold mb-1">{{ number_format($totalClicks) }}</h3>
              <small class="text-muted">{{ $totalClicks == 1 ? 'All-time redirect' : 'All-time redirects' }}</small>
            </div>
            <div class="avatar bg-light-primary p-2 rounded">
              <i class="fa-solid fa-arrow-pointer fa-xl text-primary"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Unique Visitors -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28c76f !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">Unique {{ $uniqueVisitors == 1 ? 'Visitor' : 'Visitors' }}</span>
              <h3 class="fw-bold mb-1">{{ number_format($uniqueVisitors) }}</h3>
              <small class="text-muted">365-day cookie basis</small>
            </div>
            <div class="avatar bg-light-success p-2 rounded">
              <i class="fa-solid fa-fingerprint fa-xl text-success"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Today's Clicks -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ff9f43 !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">Today's {{ $clicksToday == 1 ? 'Click' : 'Clicks' }}</span>
              <h3 class="fw-bold mb-1">{{ number_format($clicksToday) }}</h3>
              <small class="text-muted">{{ $uniqueToday }} unique {{ $uniqueToday == 1 ? 'visitor' : 'visitors' }}</small>
            </div>
            <div class="avatar bg-light-warning p-2 rounded">
              <i class="fa-solid fa-calendar-day fa-xl text-warning"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Weekly Clicks -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #00bad1 !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">This Week</span>
              <h3 class="fw-bold mb-1">{{ number_format($clicksThisWeek) }} {{ $clicksThisWeek == 1 ? 'click' : 'clicks' }}</h3>
              <small class="text-muted">Weekly active visitors</small>
            </div>
            <div class="avatar bg-light-info p-2 rounded">
              <i class="fa-solid fa-chart-simple fa-xl text-info"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Monthly Clicks -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ea5455 !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">This Month</span>
              <h3 class="fw-bold mb-1">{{ number_format($clicksThisMonth) }} {{ $clicksThisMonth == 1 ? 'click' : 'clicks' }}</h3>
              <small class="text-muted">Monthly active visitors</small>
            </div>
            <div class="avatar bg-light-danger p-2 rounded">
              <i class="fa-solid fa-chart-line fa-xl text-danger"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Last Click Date -->
      <div class="col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4b53bc !important;">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <span class="text-muted d-block small mb-1">Last Click</span>
              @if($lastClickLog)
                <h6 class="fw-bold mb-1 mt-1">{{ $lastClickLog->clicked_at->format('M d, h:i A') }}</h6>
                <small class="text-muted">{{ $lastClickLog->clicked_at->diffForHumans() }}</small>
              @else
                <h6 class="fw-bold mb-1 mt-1 text-muted">Never</h6>
                <small class="text-muted">No logs recorded</small>
              @endif
            </div>
            <div class="avatar bg-light-secondary p-2 rounded">
              <i class="fa-regular fa-clock fa-xl text-secondary"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Click Trend Chart -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-chart-area text-primary me-2"></i>Daily Click Trend</h5>
        <span class="badge bg-label-secondary">Last 30 Days</span>
      </div>
      <div class="card-body">
        <div style="height: 300px; width: 100%;">
          <canvas id="clicksChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Distribution Card -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pie-chart text-info me-2"></i>Click Distribution</h5>
      </div>
      <div class="card-body">
        @if(count($distributionPercent) > 0)
          <div class="row">
            @foreach($distributionPercent as $type => $percent)
              <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="fw-semibold text-muted">{{ $type }}</span>
                  <span class="fw-bold text-dark">{{ $percent }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4">
            <i class="fa-solid fa-chart-pie text-muted fa-2x mb-2 d-block"></i>
            <small class="text-muted">No distribution data available yet.</small>
          </div>
        @endif
      </div>
    </div>

    <!-- Location Analytics (Country, State, City) -->
    <div class="row mb-4">
      <!-- Top Countries -->
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-globe text-success me-2"></i>Top Countries</h5>
          </div>
          <div class="table-responsive pt-0">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Country</th>
                  <th class="text-end">Clicks</th>
                  <th class="text-end">Visitors</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topCountries as $c)
                  <tr>
                    <td>
                      <span class="fs-5 me-1">{!! getFlagEmoji($c->country_code) !!}</span>
                      <span class="fw-semibold">{{ $c->country_name ?: 'Unknown' }}</span>
                    </td>
                    <td class="text-end fw-bold text-dark">{{ number_format($c->clicks) }}</td>
                    <td class="text-end text-muted small">{{ number_format($c->visitors) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <i class="fa-solid fa-flag text-muted fa-lg mb-2 d-block"></i>
                      <small class="text-muted">No country data available yet.</small>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Top States -->
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-map text-warning me-2"></i>Top States</h5>
          </div>
          <div class="table-responsive pt-0">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>State</th>
                  <th class="text-end">Clicks</th>
                  <th class="text-end">Visitors</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topStates as $s)
                  <tr>
                    <td class="fw-semibold">{{ $s->state ?: 'Unknown' }}</td>
                    <td class="text-end fw-bold text-dark">{{ number_format($s->clicks) }}</td>
                    <td class="text-end text-muted small">{{ number_format($s->visitors) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <i class="fa-solid fa-map-location text-muted fa-lg mb-2 d-block"></i>
                      <small class="text-muted">No state data available yet.</small>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Top Cities -->
      <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-city text-info me-2"></i>Top Cities</h5>
          </div>
          <div class="table-responsive pt-0">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>City</th>
                  <th class="text-end">Clicks</th>
                  <th class="text-end">Visitors</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topCities as $ci)
                  <tr>
                    <td class="fw-semibold">{{ $ci->city ?: 'Unknown' }}</td>
                    <td class="text-end fw-bold text-dark">{{ number_format($ci->clicks) }}</td>
                    <td class="text-end text-muted small">{{ number_format($ci->visitors) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <i class="fa-solid fa-city text-muted fa-lg mb-2 d-block"></i>
                      <small class="text-muted">No city data available yet.</small>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <!-- Associated Entities tables -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-hotel text-primary me-2"></i>Top Associated Hotels</h5>
          </div>
          <div class="table-responsive pt-0">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Hotel</th>
                  <th class="text-end">Clicks</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topHotels as $item)
                  <tr>
                    <td>
                      <span class="fw-bold d-block text-dark">{{ $item->hotel->name }}</span>
                      <small class="text-muted">{{ $item->hotel->city }}</small>
                    </td>
                    <td class="text-end"><span class="badge bg-label-primary px-3">{{ number_format($item->clicks) }} {{ $item->clicks == 1 ? 'click' : 'clicks' }}</span></td>
                    <td class="text-end">
                      <a href="{{ route('hotels.edit', $item->hotel->id) }}" class="btn btn-sm btn-icon btn-flat-primary" title="Edit Hotel">
                        <i class="fa-regular fa-pen-to-square"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <i class="fa-solid fa-hotel text-muted fa-lg mb-2 d-block"></i>
                      <small class="text-muted">No hotel clicks recorded yet.</small>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-utensils text-warning me-2"></i>Top Associated Restaurants</h5>
          </div>
          <div class="table-responsive pt-0">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Restaurant</th>
                  <th class="text-end">Clicks</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topRestaurants as $item)
                  <tr>
                    <td>
                      <span class="fw-bold d-block text-dark">{{ $item->restaurant->name }}</span>
                      <small class="text-muted">{{ $item->restaurant->city }}</small>
                    </td>
                    <td class="text-end"><span class="badge bg-label-warning px-3">{{ number_format($item->clicks) }} {{ $item->clicks == 1 ? 'click' : 'clicks' }}</span></td>
                    <td class="text-end">
                      <a href="{{ route('restaurants.edit', $item->restaurant->id) }}" class="btn btn-sm btn-icon btn-flat-warning" title="Edit Restaurant">
                        <i class="fa-regular fa-pen-to-square"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center py-4">
                      <i class="fa-solid fa-utensils text-muted fa-lg mb-2 d-block"></i>
                      <small class="text-muted">No restaurant clicks recorded yet.</small>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Click Activity -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-clock-rotate-left text-danger me-2"></i>Recent Click Activity (Last 25)</h5>
      </div>
      <div class="table-responsive pt-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Entity</th>
              <th>Browser</th>
              <th>Platform</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>IP Address</th>
            </tr>
          </thead>
          <tbody>
            @forelse($parsedLogs as $log)
              <tr>
                <td>{{ $log['clicked_at']->format('M d, h:i A') }}</td>
                <td>
                  <a href="{{ $log['entity_url'] }}" class="fw-bold text-dark">{{ $log['entity_name'] }}</a>
                  <span class="badge bg-label-secondary ms-1 small">{{ $log['entity_type'] }}</span>
                </td>
                <td>{{ $log['browser'] }}</td>
                <td>{{ $log['platform'] }}</td>
                <td>
                  <span class="fs-6 me-1">{!! getFlagEmoji($log['country_code']) !!}</span>
                  {{ $log['country_name'] }}
                </td>
                <td>{{ $log['state'] }}</td>
                <td>{{ $log['city'] }}</td>
                <td><code class="small">{{ $log['ip_address'] }}</code></td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <div class="text-muted">
                    <i class="fa-regular fa-circle-xmark fa-2x mb-2 d-block text-warning"></i>
                    <h6 class="fw-bold mb-1">No click activity has been recorded yet.</h6>
                    <small class="text-muted">Analytics will automatically appear after visitors click the affiliate link.</small>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Dedicated Click History Log Table -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list-check text-secondary me-2"></i>Complete Click Logs</h5>
      </div>
      <div class="table-responsive pt-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Date & Time</th>
              <th>Hotel / Restaurant</th>
              <th>Visitor ID</th>
              <th>Browser</th>
              <th>OS</th>
              <th>Country</th>
              <th>State</th>
              <th>City</th>
              <th>Referrer</th>
              <th>IP Address</th>
            </tr>
          </thead>
          <tbody>
            @forelse($clickLogs as $log)
              <tr>
                <td>{{ $log->clicked_at->format('M d, Y h:i A') }}</td>
                <td>
                  <strong>{{ $log->entity_name }}</strong>
                  <span class="badge bg-label-secondary ms-1 small">{{ $log->entity_type }}</span>
                </td>
                <td><small class="text-muted">{{ substr($log->visitor_id, 0, 8) }}...</small></td>
                <td>{{ $log->parsed_browser }}</td>
                <td>{{ $log->parsed_platform }}</td>
                <td>
                  <span class="fs-6 me-1">{!! getFlagEmoji($log->country_code) !!}</span>
                  {{ $log->country_name ?: 'Local Network / Testing' }}
                </td>
                <td>{{ $log->state ?: 'Testing Environment' }}</td>
                <td>{{ $log->city ?: 'Localhost' }}</td>
                <td><span class="text-truncate d-inline-block text-muted" style="max-width: 120px;" title="{{ $log->referer }}">{{ $log->parsed_referrer }}</span></td>
                <td><code class="small">{{ $log->masked_ip }}</code></td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">No logs available.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($clickLogs->hasPages())
        <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
          {{ $clickLogs->appends(request()->except('logs_page'))->links() }}
        </div>
      @endif
    </div>
  </div>

  <!-- Right Sidebar Quick Actions -->
  <div class="col-lg-3 col-md-12">
    <!-- Top Referrers -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-share-nodes text-primary me-2"></i>Top Referrers</h6>
        <span class="text-muted cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Some browsers and mobile apps hide referrer information due to privacy restrictions.">
          <i class="fa-solid fa-circle-info small"></i>
        </span>
      </div>
      <div class="card-body pt-0">
        @if(count($referrers) > 0)
          @foreach($referrers as $refKey => $data)
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted small">
                <i class="{{ $data['icon'] }} me-1" style="width: 16px; text-align: center;"></i> 
                {{ $data['name'] }}
                @if($refKey === 'Direct')
                  <span class="text-muted ms-1 cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="right" title="Direct = Visitor opened the website without a detectable referrer.">
                    <i class="fa-solid fa-circle-question" style="font-size: 0.75rem;"></i>
                  </span>
                @endif
              </span>
              <span class="badge bg-label-primary px-3 rounded-pill">{{ number_format($data['count']) }}</span>
            </div>
          @endforeach
        @else
          <small class="text-muted">No referrers logged.</small>
        @endif
      </div>
    </div>

    <!-- Browser Analytics -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-window-restore text-warning me-2"></i>Browser Breakdown</h6>
      </div>
      <div class="card-body pt-0">
        @if(count($browsers) > 0)
          @foreach($browserPercentages as $b => $percent)
            @if($browsers[$b] > 0)
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="text-muted small">{{ $b }}</span>
                  <span class="fw-bold small">{{ $browsers[$b] }} {{ $browsers[$b] == 1 ? 'click' : 'clicks' }} ({{ $percent }}%)</span>
                </div>
                <div class="progress" style="height: 6px;">
                  <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percent }}%"></div>
                </div>
              </div>
            @endif
          @endforeach
        @else
          <small class="text-muted">No browser statistics available.</small>
        @endif
      </div>
    </div>

    <!-- Device Analytics -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-mobile-screen text-info me-2"></i>Device Statistics</h6>
      </div>
      <div class="card-body pt-0">
        @if(count($devices) > 0)
          @foreach($devicePercentages as $d => $percent)
            @if($devices[$d] > 0)
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="text-muted small">{{ $d }}</span>
                  <span class="fw-bold small">{{ $devices[$d] }} {{ $devices[$d] == 1 ? 'click' : 'clicks' }} ({{ $percent }}%)</span>
                </div>
                <div class="progress" style="height: 6px;">
                  <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%"></div>
                </div>
              </div>
            @endif
          @endforeach
        @else
          <small class="text-muted">No device statistics available.</small>
        @endif
      </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0 pt-4">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-gears text-secondary me-2"></i>Quick Actions</h6>
      </div>
      <div class="card-body pt-0">
        <div class="d-grid gap-2">
          <a href="{{ route('affiliate-links.edit', $affiliateLink->id) }}" class="btn btn-outline-primary text-start">
            <i class="fa-regular fa-pen-to-square me-2"></i> Edit Affiliate Link
          </a>
          <button class="btn btn-outline-secondary text-start" onclick="copyUrl('{{ $affiliateLink->link }}', this)">
            <i class="fa-regular fa-copy me-2"></i> Copy Destination URL
          </button>
          <a href="{{ $affiliateLink->link }}" target="_blank" class="btn btn-outline-info text-start">
            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Open Destination URL
          </a>
          
          <a href="{{ route('affiliate-links.status', ['id' => $affiliateLink->id, 'status' => $affiliateLink->is_active ? 0 : 1]) }}" class="btn btn-outline-warning text-start">
            @if($affiliateLink->is_active)
              <i class="fa-regular fa-circle-pause me-2"></i> Disable Link
            @else
              <i class="fa-regular fa-circle-play me-2"></i> Enable Link
            @endif
          </a>

          <form action="{{ route('affiliate-links.destroy', $affiliateLink->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this affiliate link? All logs will be deleted permanently.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger text-start w-100">
              <i class="fa-regular fa-trash-can me-2"></i> Delete Link
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  function copyUrl(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
      setTimeout(() => {
        btn.innerHTML = originalText;
      }, 2000);
    });
  }

  document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('clicksChart').getContext('2d');
    
    // Create soft theme gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(115, 103, 240, 0.35)');
    gradient.addColorStop(1, 'rgba(115, 103, 240, 0.01)');

    const chartDays = {!! json_encode($chartDays) !!};
    const chartData = {!! json_encode($chartData) !!};

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartDays,
        datasets: [{
          label: 'Clicks',
          data: chartData,
          borderColor: '#7367f0',
          borderWidth: 3,
          backgroundColor: gradient,
          fill: true,
          tension: 0.3,
          pointBackgroundColor: '#7367f0',
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              color: '#9ea5b1'
            },
            grid: {
              color: '#f0f0f2'
            }
          },
          x: {
            ticks: {
              color: '#9ea5b1'
            },
            grid: {
              display: false
            }
          }
        }
      }
    });
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>
@endsection
