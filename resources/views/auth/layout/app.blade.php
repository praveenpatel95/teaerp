<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->

<!-- Mirrored from byrushan.com/projects/ma/1-7-1/jquery/light/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Feb 2017 08:09:57 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Vendor CSS -->
    <link href="{{asset('theme/vendors/bower_components/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css')}}" rel="stylesheet">

    <!-- CSS -->
    <link href="{{asset('theme/css/app_1.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/css/app_2.min.css')}}" rel="stylesheet">
</head>

<body>
@yield('content')



<!-- Javascript Libraries -->
<script src="{{asset('theme/vendors/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('theme/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>

<script src="{{asset('theme/vendors/bower_components/Waves/dist/waves.min.js')}}"></script>

<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="{{asset('theme/vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js')}}"></script>
<![endif]-->

<script src="{{asset('theme/js/app.min.js')}}"></script>
</body>
</html>
