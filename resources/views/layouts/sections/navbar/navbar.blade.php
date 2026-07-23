@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">
            @include('_partials.macros',["height"=>20])
          </span>
          <span class="app-brand-text demo menu-text fw-bold">{{config('variables.')}}</span>
        </a>
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="ti ti-menu-2 ti-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">
          <!-- View Site Button -->
          <li class="nav-item me-3">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" style="padding: 4px 10px; font-size: 0.8rem; font-weight: 500; border-radius: 6px;">
              <i class="ti ti-external-link" style="font-size: 0.9rem;"></i>
              <span>View Site</span>
            </a>
          </li>
          <li class="nav-item me-2 me-xl-0">
            <span class="fw-semibold d-block">
              {{Session::get('name')}}
            </span>
          </li>
          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <!-- <a class="dropdown-item" href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}"> -->
                  <div class="dropdown-item d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-semibold d-block">
                        {{Session::get('Name')}}
                      </span>
                    </div>
                  </div>
                <!-- </a> -->
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
                <!-- change password -->
                @if(session()->get('UserDetails')->is_admin == 1)
                  <a href="{{ url('/admin/role') }}" class="dropdown-item"><i class='ti ti-id me-2'></i> Role & Permission</a>
                @endif
                <!-- change password -->
                <li>
                  <a class="dropdown-item" href="{{url('admin/changepassword')}}">
                    <span class="d-flex align-items-center align-middle">
                      <i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
                      <span class="flex-grow-1 align-middle">Change Password</span>
                    </span> </a>
                </li>
              <a href="{{ url('logout') }}" class="dropdown-item"><i class='ti ti-logout me-2'></i> Logout</a>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      <!-- Search Small Screens -->
      <div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text" class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0" placeholder="Search..." aria-label="Search...">
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
      </div>
      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>
  <!-- / Navbar -->
