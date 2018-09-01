<html lang="en">
@include('admin.layout.header')
<div id="wrapper">
@include('admin.layout.sidebar')
@yield('page')
</div>
@include('admin.layout.footer')
</html>
@yield('foot')

