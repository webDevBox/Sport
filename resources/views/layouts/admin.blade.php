<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Sports App Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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

    {{-- Emoji --}}
    <link href="{{ asset('theme/plugins/emoji/emojionearea.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- FontAwesome Kit-->
    <script src="https://kit.fontawesome.com/6926415b32.js" crossorigin="anonymous"></script>


    <!-- DataTables -->
    <link href="{{ asset('theme/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('theme/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    @yield('styles')
    <script src="{{ asset('theme/assets/js/modernizr.min.js') }}"></script>
<style>
 /* Preloader */
#preloader {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #fff;
  /* change if the mask should have another color then white */
  z-index: 9999;
  /* makes sure it stays on top */
}

#status {
  width: 200px;
  height: 200px;
  position: absolute;
  left: 50%;
  /* centers the loading animation horizontally one the screen */
  top: 50%;
  /* centers the loading animation vertically one the screen */
  background-image: url({{ asset('assets/images/ball.gif') }});
  /* path to your loading animation */
  background-repeat: no-repeat;
  background-position: center;
  margin: -100px 0 0 -100px;
  /* is width and height divided by two */
}
</style>

</head>

<body>
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>
    <div id="wrapper">
        @include('layouts.adminSidebar')

         <div class="content-page"> 

            @include('admin.dashboard.topbar')

             

            @yield('content')
            <footer class="footer">
                2021 © Sports App - Powered by: <a href="http://www.cybexo.com" class="text-danger">Cybexo Inc.</a>
            </footer>
         </div> 

        
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('theme/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/waves.js') }}"></script>
    <script src="{{ asset('theme/assets/js/jquery.slimscroll.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('theme/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    {{-- Select2 --}}
    <script src="{{ asset('theme/plugins/select2/js/select2.min.js') }}"></script>


    <!-- KNOB JS -->
    <!--[if IE]>
    <script type="text/javascript" src="plugins/jquery-knob/excanvas.js"></script>
    <![endif]-->
    <script src="{{ asset('theme/plugins/jquery-knob/jquery.knob.js') }}"></script>

    <!-- Counter Up  -->
    <script src="{{ asset('theme/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('theme/plugins/counterup/jquery.counterup.min.js') }}"></script>

    @yield('scripts')
    <!-- App js -->
    <script src="{{ asset('theme/assets/js/jquery.core.js') }}"></script>
    <script src="{{ asset('theme/assets/js/jquery.app.js') }}"></script>

    {{-- Emoji --}}
    <script src="{{ asset('theme/plugins/emoji/emojionearea.min.js') }}"></script>
        <script type="text/javascript">
            // Textarea Emoji
            $(document).ready(function() {
                $("#emojionearea1").emojioneArea({
                pickerPosition: "left",
                tonesStyle: "bullet"
              });
                $("#emojionearea2").emojioneArea({
                pickerPosition: "bottom",
                tonesStyle: "radio"
              });
                $("#emojionearea3").emojioneArea({
                pickerPosition: "left",
                filtersPosition: "bottom",
                tonesStyle: "square"
              });
                $("#emojionearea4").emojioneArea({
                pickerPosition: "bottom",
                filtersPosition: "bottom",
                tonesStyle: "checkbox"
              });
                $("#emojionearea5").emojioneArea({
                pickerPosition: "top",
                filtersPosition: "bottom",
                tones: false,
                autocomplete: false,
                inline: true,
                hidePickerOnBlur: false
              });
            });
        </script>
        <script>
          $(window).on('load', function() { // makes sure the whole site is loaded 
  $('#status').fadeOut(); // will first fade out the loading animation 
  $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
  $('body').delay(350).css({'overflow':'visible'});
})
        </script>
</body>
</html>
