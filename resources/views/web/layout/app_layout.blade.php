<!DOCTYPE html>
<html lang="en">
  <head>
    @include('web.layout.sections.head')
  </head>
  <body class="home page-template page-template-index page-template-index-php page page-id-2 page-parent main-layout">
    <!-- Global Page Loader -->
    <div id="global-loader" class="global-loader">
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">Michigan Explorer</div>
        </div>
    </div>
    
    <!-- Reading Progress Bar (Global) -->
    <div class="reading-progress-container">
        <div class="reading-progress-bar" id="readingProgressBar"></div>
    </div>
    @include('web.layout.sections.header')
    <main id="main-content">
        @yield('webLayoutContent')
    </main>
    @include('web.layout.sections.footer')
    @include('web.layout.sections.script')
    @yield('webLayoutScript')
  </body>
</html>