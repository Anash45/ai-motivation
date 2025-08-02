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
                            Subscribe for $9.99/month
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- PayPal (Disabled) -->
        <div class="col-md-6">
            <div class="card bg-secondary text-white p-4 shadow rounded position-relative">
                <h3 class="mb-3 text-center">Subscribe with PayPal</h3>
                <p class="text-center mb-4">Coming Soon: Pay with your PayPal account.</p>

                <div class="d-grid">
                    <button class="btn btn-light btn-lg disabled" disabled>
                        PayPal Coming Soon
                    </button>
                </div>

                <div class="position-absolute top-0 end-0 p-2">
                    <span class="badge bg-warning text-dark">Coming soon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
