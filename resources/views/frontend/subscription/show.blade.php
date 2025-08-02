@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-start min-vh-100 pt-5 text-white"
        style="background-color: #121212;">
        <div class="card bg-dark text-white p-5 shadow rounded mt-5" style="max-width: 500px; width: 100%;">
            <h2 class="mb-3 text-center">Upgrade to Premium</h2>
            <p class="mb-4 text-center">Get unlimited daily motivational audios tailored for you.</p>

            <form action="{{ route('subscription.checkout') }}" method="POST">
                @csrf
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg">
                        Subscribe for $9.99/month
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection