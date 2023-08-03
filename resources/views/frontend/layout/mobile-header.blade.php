<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{url('/')}}"><img src="{{ asset('assets/imgs/theme/logo.png') }}" alt="logo" /></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…" />
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        <li class="">
                            <a href="{{url('/')}}">Home</a>
                        </li>
                        <!--<li class="menu-item-has-children">-->
                        <!--    <a href="shop-grid-right.html">shop</a>-->
                        <!--    <ul class="dropdown">-->
                        <!--        <li class="menu-item-has-children">-->
                        <!--            <a href="#">Category One</a>-->
                        <!--            <ul class="dropdown">-->
                        <!--                <li><a href="#">Sub One</a></li>-->
                        <!--                <li><a href="#">Sub Two</a></li>-->
                        <!--            </ul>-->
                        <!--        </li>-->
                        <!--        <li class="menu-item-has-children">-->
                        <!--            <a href="#">Category One</a>-->
                        <!--            <ul class="dropdown">-->
                        <!--                <li><a href="#">Sub One</a></li>-->
                        <!--                <li><a href="#">Sub Two</a></li>-->
                        <!--            </ul>-->
                        <!--        </li>-->
                                
                        <!--    </ul>-->
                        <!--</li>-->
                        <!--<li><a href="{{url('track-order')}}">Order Tracking</a></li>-->
                        @if(!Auth::check())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Registration</a></li>
                        @else
                            @if(Auth::user()->type == '1')
                            <li><a href="/dashboard">Dashboard</a></li>
                            @elseif(Auth::user()->type == '2')
                            <li><a href="/dashboard">Dashboard</a></li>
                            @elseif(Auth::user()->hasRole('User'))
                            <li><a href="/user/dashboard">Dashboard</a></li>
                            @endif
                            <li><a href="{{ url('/login') }}">Logout</a></li>
                        @endif
                        
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <!--<div class="mobile-header-info-wrap">-->
            <!--    <div class="single-mobile-header-info">-->
            <!--        <a href="page-contact.html"><i class="fi-rs-marker"></i> Our location </a>-->
            <!--    </div>-->
            <!--    <div class="single-mobile-header-info">-->
            <!--        <a href="page-login.html"><i class="fi-rs-user"></i>Log In / Sign Up </a>-->
            <!--    </div>-->
            <!--    <div class="single-mobile-header-info">-->
            <!--        <a href="#"><i class="fi-rs-headphones"></i>(+01) - 2345 - 6789 </a>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="mobile-social-icon mb-50">-->
            <!--    <h6 class="mb-15">Follow Us</h6>-->
            <!--    <a href="#"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg')}}"-->
            <!--            alt="" /></a>-->
            <!--    <a href="#"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg')}}"-->
            <!--            alt="" /></a>-->
            <!--    <a href="#"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg')}}"-->
            <!--            alt="" /></a>-->
            <!--    <a href="#"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-pinterest-white.svg')}}"-->
            <!--            alt="" /></a>-->
            <!--    <a href="#"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-youtube-white.svg')}}"-->
            <!--            alt="" /></a>-->
            <!--</div>-->
            <div class="site-copyright">Copyright 2023 © Safeer Market. All rights reserved.</div>
        </div>
    </div>
</div>