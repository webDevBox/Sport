<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    @yield('metadata')
    <title>{{ config('app.name') }} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- App css -->
    <link href="{{ asset('theme/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <!-- FontAwesome Kit-->
    <script src="https://kit.fontawesome.com/6926415b32.js" crossorigin="anonymous"></script>

    @yield('styles')
    <script src="{{ asset('theme/assets/js/modernizr.min.js') }}"></script>
</head>

<body>
    <div id="wrapper">
         <div class="container-fluid">
          <!-- Header -->
            @yield('content')
            
         </div> 

        
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('theme/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/waves.js') }}"></script>
    <script src="{{ asset('theme/assets/js/jquery.slimscroll.js') }}"></script>

    @yield('scripts')
    <!-- App js -->
    <script src="{{ asset('theme/assets/js/jquery.core.js') }}"></script>
    <script src="{{ asset('theme/assets/js/jquery.app.js') }}"></script>
</body>
</html>
