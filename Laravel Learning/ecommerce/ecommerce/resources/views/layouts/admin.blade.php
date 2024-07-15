<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Commerce Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <!-- Styles -->
    <link href="{{ asset('admin/css/material-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet">
    
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>

<style>
    .custom-border{
        border: 1px solid #A9A9A9;
        text-align: left;
    }

    .custom-border:active{
      border: 1px solid #A9A9A9;
    }
    .custom-border:focus{
      border: 1px solid #A9A9A9;
    }
</style>

<body class="g-sidenav-show  bg-gray-200">
  @include('layouts.inc.adminnav')

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    @include('layouts.inc.sidebar')

    <div class="container-fluid py-4">
      @yield('content')

    </div>

    @include('layouts.inc.adminfooter')
  </main>

    <!-- Scripts -->
    <script src="{{asset('admin/js/popper.min.js')}}"></script>
    <script src="{{asset('admin/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/js/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('admin/js/smooth-scrollbar.min.js')}}"></script>
    <script src="{{asset('admin/js/chartjs.min.js')}}"></script>

    @yield('scripts')
</body>
</html>
