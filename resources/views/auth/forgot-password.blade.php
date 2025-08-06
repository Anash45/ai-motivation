@extends('layouts.app')

@section('content')
    <!-- join-vibe.blade.php -->
    <section class="page-section bg-dark-1 light-content" id="join">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-8 col-xl-7 offset-xl-1 wow fadeInUp mx-auto">
                    <div class="row g-0">
                        <div class="col-md-8 mx-auto">
                            <div class="bg-border-dark round p-4 position-relative z-index-1">

                                <h4 class="h3 mb-2 text-center">Forgot Password</h4>
                                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="form join-form needs-validation" id="join_form" novalidate method="POST"
                                    action="{{ route('password.email') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="input-lg input-circle form-control" placeholder="Email" required
                                            value="{{ old('email') }}">
                                    </div>

                                    <button
                                        class="submit_btn btn btn-mod btn-color btn-large btn-full btn-circle btn-hover-anim">
                                        <span>Send Password Reset Link</span>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection