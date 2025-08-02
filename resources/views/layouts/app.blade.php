<!DOCTYPE html>
<html lang="en">

<head>

    <title>VIBE Lift Daily</title>
    <meta name="description" content="Resonance &mdash; One & Multi Page Creative Template">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="https://themeforest.net/user/bestlooker/portfolio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="icon" href="images/favicon.png" type="image/png" sizes="any">
    <link rel="icon" href="https://resonance.bestlooker.pro/images/favicon.svg" type="image/svg+xml">

    @include('layouts.common.styles')

</head>

<body class="appear-animate dark-mode">

    <!-- Page Loader -->
    <div class="page-loader dark">
        <div class="loader">Loading...</div>
    </div>
    <!-- End Page Loader -->

    <!-- Skip to Content -->
    <a href="slick-one-page-dark.html#main" class="btn skip-to-content">Skip to Content</a>
    <!-- End Skip to Content -->

    <!-- Page Wrap -->
    <div class="page bg-dark-1" id="top">

        <!-- Navigation Panel -->
        @include('layouts.common.navbar')
        <!-- End Navigation Panel -->


        <main id="main">
            @yield('content')
        </main>
        <!-- Footer -->
        @include('layouts.common.footer')
        <!-- End Footer -->

    </div>
    <!-- End Page Wrap -->

    <!-- JS -->
    @include('layouts.common.scripts')
    <!-- End JS -->

</body>

</html>