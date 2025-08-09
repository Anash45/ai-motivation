<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{asset("assets/images/favicon.png")}}" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/dashboard.css") }}">
    @stack('styles')
</head>

<body class="bg-dark">

    <header class="navbar navbar-dark bg-dark sticky-top bg-dark flex-xl-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="{{ route('home') }}">{{env('APP_NAME')}}</a>
        <button class="navbar-toggler position-absolute d-xl-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav d-xl-block d-none">
            <div class="nav-item text-nowrap">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white px-3 bg-transparent border-0">
                        <i class="fa fa-sign-out"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-xxl-2 col-xl-3 d-xl-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                aria-current="page" href="{{ route('dashboard') }}">
                                <i class="fa fa-bar-chart"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="fa fa-users"></i>
                                Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.quotes.*') ? 'active' : '' }}"
                                href="{{ route('admin.quotes.index') }}">
                                <i class="fa fa-envelope"></i>
                                Quotes
                            </a>
                        </li>
                        <hr class="bg-white">
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('home') }}">
                                <i class="fa fa-home"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item d-xl-none d-block">
                            <form action="{{ route('logout') }}" method="post">
                                <span class="nav-link" href="{{ route('logout') }}">
                                    <i class="fa fa-sign-out"></i>
                                    Logout
                                </span>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-xxl-10 col-xl-9 ms-sm-auto px-md-4 text-white">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>
    @stack('scripts')
</body>

</html>