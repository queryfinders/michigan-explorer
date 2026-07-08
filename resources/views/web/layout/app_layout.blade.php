<!DOCTYPE html>
<html lang="en">
  <head>
    @include('web.layout.sections.head')
  </head>
  <body class="home page-template page-template-index page-template-index-php page page-id-2 page-parent main-layout">

    @include('web.layout.sections.header')
    @yield('webLayoutContent')
    @include('web.layout.sections.footer')
    @include('web.layout.sections.script')
    @yield('webLayoutScript')
  </body>
</html>