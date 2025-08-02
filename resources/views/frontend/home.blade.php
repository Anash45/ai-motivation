@extends('layouts.app')
@section('content')

    <!-- Home Section -->
    <section class="home-section bg-dark-1 light-content" id="home">

        <!-- Background Shape -->
        <div class="bg-shape-2 opacity-003">
            <img src="{{asset("/assets/images/demo-slick/bg-shape-2.svg")}}" alt="" />
        </div>
        <!-- End Background Shape -->

        <div
            class="container position-relative min-height-100vh d-flex align-items-center pt-100 pb-100 pt-md-120 pb-md-120">

            <!-- Home Section Content -->
            <div class="home-content text-start">
                <div class="row">

                    <!-- Home Section Text -->
                    <div class="col-md-10 offset-md-1 col-lg-6 offset-lg-0 d-flex align-items-center mb-md-60 mb-sm-30">
                        <div class="w-100 text-center text-lg-start">

                            <h1 class="hs-title-10 mb-40 mb-sm-20 wow fadeInUp">
                                Start your day with powerful
                                <span class="visually-hidden">purpose, confidence, clarity, focus</span>
                                <span data-period="3250" data-type='[ "purpose", "confidence", "clarity", "focus"]'
                                    class="typewrite color-primary-1" aria-hidden="true"><span class="wrap"></span></span>
                            </h1>

                            <div class="row">
                                <div class="col-lg-10">
                                    <p class="section-descr mb-50 mb-sm-40 wow fadeInUp" data-wow-delay="0.15s">
                                        VIBE Lift Daily delivers AI-powered motivation tailored to you. Our mission is
                                        to spark action, one quote at a time.
                                    </p>
                                </div>
                            </div>

                            <div class="local-scroll wow fadeInUp wch-unset" data-wow-delay="0.3s" data-wow-offset="0">

                                <a href="/register"
                                    class="btn btn-mod btn-color btn-large btn-circle btn-hover-anim mb-xs-10">
                                    <span>Start trial</span>
                                </a>

                                <a href="#about" class="link-hover-anim ms-2 ms-sm-5 me-2" data-link-animate="y">Learn
                                    more <i class="mi-arrow-right size-24"></i></a>

                            </div>

                        </div>
                    </div>
                    <!-- End Home Section Text -->

                    <!-- Images -->
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="w-100 ps-xl-3 wow fadeInLeft" data-wow-delay="0.15s">
                            <div class="composition-4">

                                <div class="composition-4-decoration opacity-065">
                                    <img src="{{ asset("assets/images/demo-slick/decoration-1.svg") }}" alt="" />
                                </div>

                                <div class="composition-4-image-1">
                                    <div class="composition-4-image-1-inner">
                                        <img src="{{ asset("assets/images/demo-slick/hs-image-2.jpg") }}"
                                            alt="Image Description" />
                                    </div>
                                </div>

                                <div class="composition-4-image-2">
                                    <div class="composition-4-image-2-inner">
                                        <img src="{{ asset("assets/images/demo-slick/hs-image-1.jpg") }}"
                                            alt="Image Description" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Images -->

                </div>
            </div>
            <!-- End Home Section Content -->

        </div>

    </section>
    <!-- End Home Section -->


    <!-- Divider -->
    <hr class="mt-0 mb-0 white" />
    <!-- End Divider -->


    <!-- About Section -->
    <section class="page-section bg-dark-1 light-content" id="about">
        <div class="container position-relative">

            <div class="row">

                <!-- Section Text -->
                <div class="col-lg-6 d-flex align-items-center order-first order-lg-last mb-md-60 mb-sm-40">
                    <div class="w-100 wow fadeInUp">

                        <h2 class="section-caption-slick mb-30 mb-sm-20">
                            About VIBE Lift Daily
                        </h2>

                        <h3 class="section-title mb-30">
                            We believe in daily growth through personalized motivation.


                        </h3>

                        <p class="text-gray mb-40">
                            VIBE Lift Daily blends AI with psychology to craft quotes that truly resonate. We focus on
                            helping individuals build clarity, purpose, and momentum - one message at a time. Every
                            quote is shaped around your lifestyle and mindset, ensuring you get more than inspiration -
                            you get alignment. With a thoughtful user experience and a mission rooted in human impact,
                            we're changing how motivation works.


                        </p>

                        <!-- Features List -->
                        <div class="row features-list mt-n20 mb-50 mb-sm-30">

                            <!-- Features List Item -->
                            <div class="col-sm-6 col-lg-12 col-xl-6 d-flex mt-20">
                                <div class="features-list-icon">
                                    <i class="mi-check"></i>
                                </div>
                                <div class="features-list-text">
                                    Built on real human emotion and AI intelligence

                                </div>
                            </div>
                            <!-- End Features List Item -->

                            <!-- Features List Item -->
                            <div class="col-sm-6 col-lg-12 col-xl-6 col-lg-6 d-flex mt-20">
                                <div class="features-list-icon">
                                    <i class="mi-check"></i>
                                </div>
                                <div class="features-list-text">
                                    Designed to motivate, inspire, and empower daily

                                </div>
                            </div>
                            <!-- End Features List Item -->

                            <!-- Features List Item -->
                            <div class="col-sm-6 col-lg-12 col-xl-6 d-flex mt-20">
                                <div class="features-list-icon">
                                    <i class="mi-check"></i>
                                </div>
                                <div class="features-list-text">
                                    Custom-tailored to your goals, habits, and mindset

                                </div>
                            </div>
                            <!-- End Features List Item -->

                            <!-- Features List Item -->
                            <div class="col-sm-6 col-lg-12 col-xl-6 d-flex mt-20">
                                <div class="features-list-icon">
                                    <i class="mi-check"></i>
                                </div>
                                <div class="features-list-text">
                                    Developed for simplicity, speed, and consistency


                                </div>
                            </div>
                            <!-- End Features List Item -->

                        </div>
                        <!-- End Features List -->

                        <div class="local-scroll wch-unset">

                            <a href="/register" class="btn btn-mod btn-color btn-large btn-circle btn-hover-anim mb-xs-10">
                                <span>Start trial</span>
                            </a>

                        </div>

                    </div>
                </div>
                <!-- End Section Text -->

                <!-- Image -->
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="w-100 pe-lg-5">
                        <div class="composition-5">

                            <div class="composition-5-decoration opacity-065">
                                <img src="{{ asset("assets/images/demo-slick/decoration-1.svg") }}" alt="" />
                            </div>

                            <div class="composition-5-image-1">
                                <div class="composition-5-image-1-inner">
                                    <img src="{{ asset("assets/images/demo-slick/hs-image-3.jpg") }}"
                                        alt="Image Description" class="wow scaleOutIn" data-wow-offset="200" />
                                </div>
                            </div>

                            <div class="composition-5-image-2">
                                <div class="composition-5-image-2-inner">
                                    <img src="{{ asset("assets/images/demo-slick/hs-image-4.jpg") }}"
                                        alt="Image Description" class="wow scaleOutIn" data-wow-offset="0" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Images -->

            </div>

        </div>
    </section>
    <!-- End About Section -->


    <!-- Numbers Section -->
    <section class="page-section bg-dark-1 bg-dark-alpha light-content bg-fixed"
        style="background-image: url(/assets/images/demo-slick/looking-up.jpg)">
        <div class="container position-relative">

            <!-- Numbers Grid -->
            <div class="row mt-n30 wow fadeInUp">

                <!-- Numbers Item -->
                <div class="col-sm-6 col-xl-3 d-flex align-items-stretch mt-30">
                    <div class="number-2-item">
                        <div class="number-2-title">
                            7,300+
                        </div>
                        <div class="number-2-descr">
                            Quotes Delivered
                        </div>
                    </div>
                </div>
                <!-- End Numbers Item -->

                <!-- Numbers Item -->
                <div class="col-sm-6 col-xl-3 d-flex align-items-stretch mt-30">
                    <div class="number-2-item">
                        <div class="number-2-title">
                            98%
                        </div>
                        <div class="number-2-descr">
                            Users Feel Uplifted
                        </div>
                    </div>
                </div>
                <!-- End Numbers Item -->

                <!-- Numbers Item -->
                <div class="col-sm-6 col-xl-3 d-flex align-items-stretch mt-30">
                    <div class="number-2-item">
                        <div class="number-2-title">
                            1,200+
                        </div>
                        <div class="number-2-descr">
                            Profiles Inspired
                        </div>
                    </div>
                </div>
                <!-- End Numbers Item -->

                <!-- Numbers Item -->
                <div class="col-sm-6 col-xl-3 d-flex align-items-stretch mt-30">
                    <div class="number-2-item">
                        <div class="number-2-title">
                            Zero
                        </div>
                        <div class="number-2-descr">
                            Generic Messages
                        </div>
                    </div>
                </div>
                <!-- End Numbers Item -->

            </div>
            <!-- End Numbers Grid -->

        </div>
    </section>
    <!-- End Numbers Section -->



    <!-- Contact Section -->
    <section class="page-section pb-0 bg-dark-1 light-content" id="contact">
        <div class="container position-relative">

            <div class="row">
                <!-- Right Column -->
                <div class="col-lg-8 col-xl-7 offset-xl-1 wow fadeInUp mx-auto">
                    <div class="row g-0">

                        <!-- Contact Form Column -->
                        <div class="col-md-8 mx-auto">
                            <div class="bg-border-dark round p-4 position-relative z-index-1">

                                <h4 class="h3 mb-40 mb-sm-30 text-center">Get in Touch</h4>

                                <!-- Contact Form -->
                                <form class="form contact-form" id="contact_form">

                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name" class="visually-hidden">Name</label>
                                        <input type="text" name="name" id="name" class="input-lg input-circle form-control"
                                            placeholder="Name" pattern=".{3,100}" required aria-required="true">
                                    </div>
                                    <!-- End Name -->

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="input-lg input-circle form-control" placeholder="Email"
                                            pattern=".{5,100}" required aria-required="true">
                                    </div>
                                    <!-- End Email -->

                                    <!-- Message -->
                                    <div class="form-group">
                                        <label for="message" class="visually-hidden">Message</label>
                                        <textarea name="message" id="message" class="input-lg input-circle form-control"
                                            style="height: 130px;" placeholder="Message"></textarea>
                                    </div>

                                    <!-- Send Button -->
                                    <button
                                        class="submit_btn btn btn-mod btn-color btn-large btn-full btn-circle btn-hover-anim"
                                        id="submit_btn" aria-controls="result">
                                        <span>Send Message</span>
                                    </button>
                                    <!-- End Send Button -->

                                    <!-- Inform Tip -->
                                    <div class="form-tip w-100 pt-30 mt-sm-20">
                                        <i class="icon-info size-16"></i>
                                        All the fields are required. By sending the form you agree to the <a href="#">Terms
                                            & Conditions</a> and <a href="#">Privacy Policy</a>.
                                    </div>
                                    <!-- End Inform Tip -->

                                    <div id="result" role="region" aria-live="polite" aria-atomic="true"></div>

                                </form>
                                <!-- End Contact Form -->

                            </div>
                        </div>
                        <!-- End Contact Form Column -->

                    </div>
                </div>
                <!-- End Right Column -->

            </div>

        </div>
    </section>
    <!-- End Contact Section -->

    <!-- FAQ Section -->
    <section class="page-section bg-dark-1 light-content z-index-1">
        <div class="container position-relative">

            <!-- Decorative Waves -->
            <div class="position-relative">
                <div class="decoration-21 opacity-07 d-none d-lg-block" data-rellax-y data-rellax-speed="0.7"
                    data-rellax-percentage="0.35">
                    <img src="{{ asset("assets/images/demo-slick/decoration-4.svg") }}" alt="" />
                </div>
            </div>
            <!-- End Decorative Waves -->

            <div class="row position-relative">

                <div class="col-md-6 col-lg-5 mb-md-50 mb-sm-30">

                    <h3 class="section-title mb-30">
                        Frequently Asked Questions
                    </h3>

                    <p class="text-gray mb-0">
                        Got questions? We've got answers. Here's everything you need to know about how VIBE Lift Daily
                        works, how to get started, and what to expect from your daily dose of motivation.


                    </p>

                </div>

                <div class="col-md-6 offset-lg-1 pt-10 pt-sm-0">

                    <!-- Accordion -->
                    <dl class="toggle">

                        <dt>
                            <a href="#">How does VIBE Lift Daily create quotes?
                            </a>
                        </dt>
                        <dd class="black">
                            We use advanced AI trained on motivational psychology and your personal profile (age range,
                            profession, interests) to generate unique, relevant quotes just for you.


                        </dd>

                        <dt>
                            <a href="#">Is the 7-day trial really free?
                            </a>
                        </dt>
                        <dd class="black">
                            Absolutely. No credit card is required. You'll receive daily motivational quotes for 7 days,
                            with full access to your personalized dashboard.


                        </dd>

                        <dt>
                            <a href="#">How are quotes delivered each day?
                            </a>
                        </dt>
                        <dd class="black">
                            You'll get your daily quote via email, and it's also available in your dashboard.
                            Notifications can be customized in your preferences.


                        </dd>

                        <dt>
                            <a href="#">What happens after the trial ends?
                            </a>
                        </dt>
                        <dd class="black">
                            You'll be invited to subscribe via Stripe or PayPal. If you choose not to subscribe, your
                            account will pause - but your profile and quote history will be saved.


                        </dd>

                    </dl>
                    <!-- End Accordion -->

                </div>

            </div>

        </div>
    </section>
    <!-- End FAQ Section -->
@endsection