@extends('layouts.holding')

@section('title', 'Coming Soon | VIBELIFTDAILY')

@section('content')
    <main class="main-06 min-vh-100">
        <!-- header start  -->
        <div class="header header-06">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <a href="/">
                            <img src="{{asset("assets/images/logo-02.png")}}" style="height: 50px;"
                                alt="Laravel Logo">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <div class="header-right text-right">
                            <a href="mailto:vibeliftdaily.com">Say hello! info@vibeliftdaily.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header end  -->
        <!-- ========================= main-wrapper start ========================= -->
        @include('holding.main')

    </main>
@endsection