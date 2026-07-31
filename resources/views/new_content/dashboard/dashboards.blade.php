@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('content')
<div class="row">
  <!-- Welcome Block -->
  <div class="col-12 mb-4">
    <div class="card border-0 shadow-sm bg-transparent">
      <div class="card-body p-0">
        <h3 class="fw-bold mb-1">Welcome to Michigan Explorer</h3>
        <p class="text-muted mb-0">Track and manage your website stats and user inquiries.</p>
      </div>
    </div>
  </div>
</div>

<!-- Widgets Rows -->
<div class="row mb-4">
  <!-- Users -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/user') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #7367f0 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Users</span>
          <h3 class="fw-bold mb-0 text-primary">{{ number_format($counts['users'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-primary p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-users fa-lg text-primary"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Hotels -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/hotels') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #28c76f !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Hotels</span>
          <h3 class="fw-bold mb-0 text-success">{{ number_format($counts['hotels'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-success p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-building fa-lg text-success"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Restaurants -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/restaurants') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #ff9f43 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Restaurants</span>
          <h3 class="fw-bold mb-0 text-warning">{{ number_format($counts['restaurants'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-warning p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-utensils fa-lg text-warning"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Attractions -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/attractions') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #ea5455 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Attractions</span>
          <h3 class="fw-bold mb-0 text-danger">{{ number_format($counts['attractions'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-danger p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-map-location-dot fa-lg text-danger"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Events -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/events') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #00cfe8 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Events</span>
          <h3 class="fw-bold mb-0 text-info">{{ number_format($counts['events'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-info p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-calendar-days fa-lg text-info"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Blogs -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/blogs') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #6c757d !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Blogs</span>
          <h3 class="fw-bold mb-0 text-secondary">{{ number_format($counts['blogs'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-secondary p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-blog fa-lg text-secondary"></i>
        </div>
      </div>
    </a>
  </div>
  <!-- Contact Messages -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/admin/contact-messages') }}" class="card border-0 shadow-sm h-100 text-decoration-none hover-primary" style="border-left: 4px solid #7367f0 !important;">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted d-block small mb-1">Total Contact</span>
          <h3 class="fw-bold mb-0 text-primary">{{ number_format($counts['messages'] ?? 0) }}</h3>
        </div>
        <div class="avatar bg-light-primary p-2 rounded d-flex align-items-center justify-content-center">
          <i class="fa-solid fa-messages fa-lg text-primary"></i>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="row mb-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 pt-4">
        <h5 class="mb-0 fw-bold">Overview Chart</h5>
      </div>
      <div class="card-body pt-2">
        <div id="overviewChart"></div>
      </div>
    </div>
  </div>
</div>

<!-- Latest inquiries section -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4">
        <h5 class="mb-0 fw-bold"><i class="fa-regular fa-paper-plane me-2 text-primary"></i>Latest Contact Messages</h5>
        <a href="{{ route('contact-messages.index') }}" class="btn btn-warning btn-sm">View All Inquiries</a>
      </div>
      <div class="card-body pt-2">
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>SR No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <!-- <th>Status</th> -->
                <th>Submitted Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($latest_contact_messages as $index => $msg)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $msg->full_name }}</strong></td>
                <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                <td>{{ Str::limit($msg->subject, 45) }}</td>
                <!-- <td>
                  @if($msg->status === 'new')
                    <span class="badge bg-label-danger">New</span>
                  @elseif($msg->status === 'read')
                    <span class="badge bg-label-warning">Read</span>
                  @elseif($msg->status === 'replied')
                    <span class="badge bg-label-success">Replied</span>
                  @else
                    <span class="badge bg-label-secondary">Closed</span>
                  @endif
                </td> -->
                <td class="small text-muted">{{ $msg->created_at->format('M d, Y h:i A') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No contact messages received yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const overviewChartEl = document.querySelector('#overviewChart');
  if (overviewChartEl) {
    const overviewChartConfig = {
      chart: {
        height: 350,
        type: 'bar',
        toolbar: { show: false }
      },
      series: [{
        name: 'Total Count',
        data: [
          {{ $counts['users'] ?? 0 }},
          {{ $counts['hotels'] ?? 0 }},
          {{ $counts['restaurants'] ?? 0 }},
          {{ $counts['attractions'] ?? 0 }},
          {{ $counts['events'] ?? 0 }},
          {{ $counts['blogs'] ?? 0 }},
          {{ $counts['messages'] ?? 0 }}
        ]
      }],
      xaxis: {
        categories: ['Users', 'Hotels', 'Restaurants', 'Attractions', 'Events', 'Blogs', 'Inquiries'],
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      colors: ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8', '#6c757d', '#7367f0'],
      plotOptions: {
        bar: {
          borderRadius: 4,
          distributed: true,
          columnWidth: '40%'
        }
      },
      dataLabels: { enabled: false },
      legend: { show: false }
    };
    const overviewChart = new ApexCharts(overviewChartEl, overviewChartConfig);
    overviewChart.render();
  }
});
</script>
@endsection
