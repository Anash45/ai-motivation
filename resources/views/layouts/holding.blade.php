<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Sooner | SoonLaunch')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SoonLaunch">

    <link rel="shortcut icon" type="image/x-icon" href="/holding-assets/img/favicon.png">

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="/holding-assets/css/bootstrap-4.5.0.min.css">
    <link rel="stylesheet" href="/holding-assets/css/lineicons.css">
    <link rel="stylesheet" href="/holding-assets/css/animate.css">
    <link rel="stylesheet" href="/holding-assets/css/style.css">
</head>
<body>

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser.
        Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- ========================= main start ========================= -->
    @yield('content')
    <!-- ========================= main end ========================= -->

    <!-- ========================= JS here ========================= -->
    <script src="/holding-assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="/holding-assets/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="/holding-assets/js/popper.min.js"></script>
    <script src="/holding-assets/js/bootstrap-4.5.0.min.js"></script>
    <script src="/holding-assets/js/countdown.js"></script>
    <script src="/holding-assets/js/wow.min.js"></script>
    <script src="/holding-assets/js/main.js"></script>
</body>
</html>