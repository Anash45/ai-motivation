<nav class="main-nav dark dark-mode transparent stick-fixed wow-menubar wch-unset">
    <div class="main-nav-sub full-wrapper">

        <!-- Logo  (* Add your text or image to the link tag. Use SVG or PNG image format. 
                    If you use a PNG logo image, the image resolution must be equal 200% of the visible logo
                    image size for support of retina screens. See details in the template documentation. *) -->
        <div class="nav-logo-wrap local-scroll">
            <a href="#top" class="logo">
                <img src="{{asset("assets/images/logo-01.png")}}" alt="{{env('APP_NAME')}}" width="106" height="36" />
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="mobile-nav" role="button" tabindex="0">
            <i class="mobile-nav-icon"></i>
            <span class="visually-hidden">Menu</span>
        </div>

        <!-- Main Menu -->
        <div class="inner-nav desktop-nav">
            <ul class="clearlist scroll-nav local-scroll">
                <li><a href="/home#home" class="active">Home</a></li>
                <li><a href="/home#about">About</a></li>
                <li><a href="/home#contact">Contact</a></li>
                <li><a href="/home#faqs">Faqs</a></li>
            </ul>

            <ul class="items-end clearlist local-scroll d-flex align-items-center">
                <li class="pb-1">
                    <a href="/join-vibe" class="opacity-1 no-hover">
                        <span class="link-hover-anim underline" data-link-animate="y">Start trial</span>
                    </a>
                </li>
                <li>
                    <a href="/vibe-login" class="opacity-1 btn-hover-anim d-flex flex-column justify-content-center">
                        <span class="btn btn-mod btn-border-w-light btn-small btn-circle" data-btn-animate="y">Login</span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- End Main Menu -->

    </div>
</nav>