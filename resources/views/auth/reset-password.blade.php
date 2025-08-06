@extends('layouts.app')

@section('content')
    <!-- reset.blade.php -->
    <section class="page-section bg-dark-1 light-content" id="reset-password">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-8 col-xl-7 offset-xl-1 wow fadeInUp mx-auto">
                    <div class="row g-0">
                        <div class="col-md-8 mx-auto">
                            <div class="bg-border-dark round p-4 position-relative z-index-1">

                                <h4 class="h3 mb-2 text-center">Reset Password</h4>
                                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                                    {{ __('Please enter your new password below.') }}
                                </div>

                                @include('partials.messages')

                                <form method="POST" action="{{ route('password.store') }}" class="form join-form needs-validation" novalidate>
                                    @csrf

                                    <!-- Token -->
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <!-- Email -->
                                    <div class="form-group mb-3">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" id="email" name="email" class="input-lg input-circle form-control"
                                               placeholder="Email"
                                               value="{{ old('email', $request->email) }}" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-3">
                                        <label for="password" class="visually-hidden">New Password</label>
                                        <input type="password" id="password" name="password" class="input-lg input-circle form-control"
                                               placeholder="New Password" required>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-4">
                                        <label for="password_confirmation" class="visually-hidden">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="input-lg input-circle form-control"
                                               placeholder="Confirm Password" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button
                                        class="submit_btn btn btn-mod btn-color btn-large btn-full btn-circle btn-hover-anim">
                                        <span>Reset Password</span>
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
