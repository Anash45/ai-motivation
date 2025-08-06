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

                                <h4 class="h3 mb-40 mb-sm-30 text-center">Login Vibe Lift Daily</h4>
                                @include('partials.messages')
                                <form class="form join-form needs-validation" id="join_form" novalidate method="POST"
                                    action="{{ route('user.login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="input-lg input-circle form-control" placeholder="Email" required
                                            value="{{ old('email') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="visually-hidden">Password</label>
                                        <input type="password" name="password" id="password"
                                            class="input-lg input-circle form-control" placeholder="Password" required>
                                    </div>

                                    <button
                                        class="submit_btn btn btn-mod btn-color btn-large btn-full btn-circle btn-hover-anim">
                                        <span>Login</span>
                                    </button>

                                    <div class="form-tip w-100 pt-30 mt-sm-20 text-center">
                                        Doesn't have an account? <a href="{{ route('user.register') }}">Signup</a>
                                    </div>

                                    <div class="form-tip w-100 pt-30 mt-sm-20 text-center">
                                        Forgot password? <a href="{{ route('password.request') }}">Reset here</a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection