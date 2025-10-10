<!DOCTYPE html>
<html lang="en">

<head>

    <title>Vibe Lift Daily</title>
    <meta name="description" content="Resonance &mdash; One & Multi Page Creative Template">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="icon" href="{{asset("assets/images/favicon.png")}}" type="image/png">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5QSDG94BTW"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-5QSDG94BTW');
    </script>
    <!-- Meta Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1351355556593480');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1351355556593480&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

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