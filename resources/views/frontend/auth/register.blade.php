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

                                <h4 class="h3 mb-40 mb-sm-30 text-center">Join VIBE Lift Daily</h4>
                                @include('partials.messages')
                                <form class="form join-form needs-validation" id="join_form" novalidate method="POST"
                                    action="{{ route('user.register') }}">
                                    @csrf

                                    @include('partials.messages')
                                    <div class="form-group">
                                        <label for="name" class="visually-hidden">Name</label>
                                        <input type="text" name="name" id="name" class="input-lg input-circle form-control"
                                            placeholder="Name" pattern=".{3,100}" required aria-required="true"
                                            value="{{ old('name') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="input-lg input-circle form-control" placeholder="Email" required
                                            value="{{ old('email') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="age_range" class="visually-hidden">Age Range</label>
                                        <select name="age_range" id="age_range" class="input-lg input-circle form-control"
                                            required aria-required="true">
                                            <option value="" disabled {{ old('age_range') ? '' : 'selected' }}>Age Range
                                            </option>
                                            <option value="under_18" {{ old('age_range') === 'under_18' ? 'selected' : '' }}>
                                                Under 18</option>
                                            <option value="18_24" {{ old('age_range') === '18_24' ? 'selected' : '' }}>18 - 24
                                            </option>
                                            <option value="25_34" {{ old('age_range') === '25_34' ? 'selected' : '' }}>25 - 34
                                            </option>
                                            <option value="35_44" {{ old('age_range') === '35_44' ? 'selected' : '' }}>35 - 44
                                            </option>
                                            <option value="45_54" {{ old('age_range') === '45_54' ? 'selected' : '' }}>45 - 54
                                            </option>
                                            <option value="55_plus" {{ old('age_range') === '55_plus' ? 'selected' : '' }}>55+
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="profession" class="visually-hidden">Profession</label>
                                        <input type="text" name="profession" id="profession"
                                            class="input-lg input-circle form-control" placeholder="Profession"
                                            pattern=".{2,100}" required aria-required="true"
                                            value="{{ old('profession') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="interests" class="visually-hidden">Interests</label>
                                        <textarea name="interests" id="interests" class="input-lg input-circle form-control"
                                            placeholder="Your Interests (comma separated)" rows="3" required
                                            aria-required="true">{{ old('interests') }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="visually-hidden">Password</label>
                                        <input type="password" name="password" id="password"
                                            class="input-lg input-circle form-control" placeholder="Password" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="visually-hidden">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="input-lg input-circle form-control" placeholder="Confirm Password"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label class="d-block mb-2">Choose Your Plan:</label>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="plan_type" id="trial"
                                                value="trial" required {{ old('plan_type') === 'trial' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="trial">7-day Free Trial</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="plan_type" id="subscribe"
                                                value="subscribe" required {{ old('plan_type') === 'subscribe' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="subscribe">Subscribe Now</label>
                                        </div>
                                    </div>

                                    <button
                                        class="submit_btn btn btn-mod btn-color btn-large btn-full btn-circle btn-hover-anim">
                                        <span>Create Account</span>
                                    </button>

                                    <div class="form-tip w-100 pt-30 mt-sm-20 text-center">
                                        Already have an account? <a href="{{ route('user.login') }}">Sign in</a>
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