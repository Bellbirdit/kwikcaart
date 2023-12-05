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
                        @php
                            $cats= App\Models\Category::with('cbannercat')->where('order_level','!=','0')->orderBy('order_level','ASC')->get();
                        @endphp
                        @if(isset($cats) && sizeof($cats)>0)
                            @foreach($cats as $cat)
                                @php
                                    $subcats = $cat->cbannercat; 
                                @endphp
                                <li class="@if(!empty($subcats)) menu-item-has-children @endif">
                                    <a href="javascript:;" id="{{ $cat->id }}">{{ $cat->name }}</a>
                                    @foreach($subcats as $subca)
                                        <ul class="dropdown">
                                            @php
                                                $subsubb = $subca->cbannercat;
                                                $subsub = $subsubb->unique('name');
                                            @endphp
                                            <li class="@if(!empty($subsubb) && count($subsubb) > 1) menu-item-has-children @endif">
                                                <a href="{{ route('cat-products',$subca->id) }}">{{ $subca->name }}</a>
                                                @if(!empty($subsubb) && count($subsubb) > 1)
                                                    <ul class="dropdown">
                                                        @foreach($subsub as $subsu)
                                                            <li><a href="{{ route('cat-products',$subsu->id) }}">{{ $subsu->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                            
                                        </ul>
                                    @endforeach
                                </li>        
                            @endforeach
                        @endif
                        
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
            <div class="site-copyright">Copyright 2023 © Kwikcaart. All rights reserved.</div>
        </div>
    </div>
</div>