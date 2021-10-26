
<!DOCTYPE html>
<html class="ie9">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Vendor CSS -->
    <link href="{{asset('theme/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet">

@stack('headerscript')
<!-- CSS -->
    <link href="{{asset('theme/css/app_1.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/css/app_2.min.css')}}" rel="stylesheet">


</head>
<body>
@include('salesman.layout.header')



<section id="main">
    @yield('content')

    <!-- Page Loader -->
        <div class="page-loader">
            <div class="preloader pls-blue">
                <svg class="pl-circular" viewBox="25 25 50 50">
                    <circle class="plc-path" cx="50" cy="50" r="20"/>
                </svg>
                <p>Please wait...</p>
            </div>
        </div>
</section>


@include('salesman.layout.footer')

<!-- Javascript Libraries -->
<script src="{{asset('theme/vendors/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<script src="{{asset('theme/vendors/bower_components/flot/jquery.flot.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/flot/jquery.flot.resize.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/flot.curvedlines/curvedLines.js')}}"></script>
<script src="{{asset('theme/vendors/sparklines/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/Waves/dist/waves.min.js')}}"></script>
<script src="{{asset('theme/vendors/bootstrap-growl/bootstrap-growl.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/sweetalert2/dist/sweetalert2.min.js')}}"></script>

@stack('footerscript')
@include('common.messege')


<script src="{{asset('theme/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js')}}"></script>


<script src="{{asset('theme/js/app.min.js')}}"></script>

</body>

</html>

