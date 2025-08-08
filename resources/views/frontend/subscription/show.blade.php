@extends('layouts.app')

@section('content')
    <div class="container min-vh-100 pt-5 text-white" style="background-color: #121212;">
        <div class="row justify-content-center mt-5 pt-5">
            <!-- Card Payment -->
            <div class="col-md-6">
                <div class="card bg-dark text-white p-4 shadow rounded">
                    <h3 class="mb-3 text-center">Subscribe with Card</h3>
                    <p class="text-center mb-4">Get unlimited daily motivational audios tailored for you.</p>

                    <form action="{{ route('subscription.checkout') }}" method="POST">
                        @csrf
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">
                                Subscribe for &pound;9.99/month
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- PayPal -->
            <div class="col-md-6 d-none">
                <div class="card bg-dark text-white p-4 shadow rounded">
                    <h3 class="mb-3 text-center">Subscribe with PayPal</h3>
                    <p class="text-center mb-4">Use your PayPal account to subscribe.</p>

                    <div class="d-grid">
                        <a href="#" class="btn btn-warning btn-lg">
                            Subscribe with PayPal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection