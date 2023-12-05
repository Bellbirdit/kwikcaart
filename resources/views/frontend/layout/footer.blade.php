<footer class="main">
    <section class="section-padding footer-mid">
        <div class="container-fluid pt-15 pb-20">
            <div class="row">
                <div class="col">
                    <div class="widget-about font-md mb-md-3 mb-lg-3 mb-xl-0 "
                        data-wow-delay="0">
                        <div class="logo mb-30">
                            <a href="{{ url('/') }}" class="mb-15">
                                <img class="w-75"
                                    src="{{ asset('assets/imgs/theme/logo.png') }}"
                                    alt="logo" />
                            </a>
                        </div>
                        <div class="footer-link-widget widget-install-app col "
                            data-wow-delay=".5s">
                            <h4 class="widget-title">Install App</h4>
                            <div class="download-app">
                                <a href="javascript:;" class="hover-up mb-sm-2 mb-lg-0"><img class="active"
                                        src="{{ asset('frontend/assets/imgs/theme/app-store.jpg') }}"
                                        alt="" /></a>
                                <a href="javascript:;" class="hover-up mb-sm-2"><img
                                        src="{{ asset('frontend/assets/imgs/theme/google-play.jpg') }}"
                                        alt="" /></a>
                            </div>
                            <img class=""
                                src="{{ asset('frontend/assets/imgs/theme/payment-method.png') }}"
                                alt="" />
                        </div>

                    </div>
                </div>
                @php
                    $fcontents=App\Models\FooterSetting::all();
                @endphp
                @if(isset($fcontents) && sizeof ($fcontents) > '0')
                    @foreach($fcontents as $fcontent)
                        <div class="footer-link-widget col"
                            data-wow-delay=".3s">
                            <h4 class="widget-title">Info</h4>
                            <ul class="contact-infor">
                                <li>
                                    <!--<img src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}"alt="" />-->
                                    <strong>Address: </strong> <span>{{ $fcontent->address }}</span>
                                </li>
                                <!--<li><img src="{{ asset('frontend/assets/imgs/theme/icons/icon-contact.svg') }}"alt="" />-->
                                <strong>Call Us:</strong><span>{{ $fcontent->phone }}</span>
                                </li>
                                <li>
                                    <!--<img src="{{ asset('frontend/assets/imgs/theme/icons/icon-email-2.svg') }}" alt="" />-->
                                    <strong>Email:</strong><span>{{ $fcontent->info_email }}</span>
                                </li>
                                <li>
                                    <!--<img src="{{ asset('frontend/assets/imgs/theme/icons/icon-clock.svg') }}" alt="" />-->
                                    <!-- <strong>Hours:</strong><span>10:00 - 18:00, Mon - Sat</span> -->
                                </li>
                            </ul>
                            <form class="form-subcriber">
                                <input type="email" placeholder="Your emaill address" />
                                <button class="btn" style="margin-top:10px;" type="submit">Subscribe</button>
                            </form>
                        </div>
                        <div class="footer-link-widget col"
                            data-wow-delay=".1s">
                            <h4 class=" widget-title">Important Links</h4>
                            <ul class="footer-list mb-sm-5 mb-md-0">
                                <li><a href="{{ $fcontent->about_us }}">About Us</a></li>
                                <li><a href="{{ $fcontent->contact_us }}">Contact Us</a></li>
                                <li><a href="{{ $fcontent->privacy_policy }}">Privacy Policy</a></li>
                                <li><a href="{{ $fcontent->terms }}">Terms &amp; Conditions</a></li>
                                <li><a href="{{ $fcontent->support }}">Support 24/7</a></li>

                            </ul>

                        </div>
                   
                <div class="footer-link-widget col" data-wow-delay=".2s">
                    <h4 class="widget-title">Account</h4>
                    <ul class="footer-list mb-sm-5 mb-md-0">
                        <li><a href="{{url('/login')}}">Sign In</a></li>
                        <li><a href="{{url('user/dashboard')}}">My Wishlist</a></li>
                        <li><a href="{{url('user/dashboard')}}">Track My Order</a></li>
                        <li><a href="{{url('user/dashboard')}}">Help Ticket</a></li>
                        <li><a href="{{ $fcontent->shipping_details }}">Shipping Details</a></li>
                    </ul>
                </div>
                <div class="footer-link-widget col" data-wow-delay=".4s">
                    <h4 class="widget-title">Our Services</h4>
                    <ul class="footer-list mb-sm-5 mb-md-0">
                        <!--<li><a href="">Brands</a></li>-->
                        <!--<li><a href="{{ $fcontent->safeer_plus }}">Safeer Plus</a></li>-->
                        <li><a href="{{ $fcontent->faq }}">FAQs</a></li>
                        <li><a href="{{ $fcontent->our_stores }}">Our Stores</a></li>
                        <li><a href="{{ $fcontent->return_policy }}">Return & Exchange Policy</a></li>
                        <li><a href="{{ $fcontent->service_warrenty }}"> Service & Warranty</a></li>
                    </ul>
                </div>
                 @endforeach
                @endif
            </div>
    </section>
    <div class="container-fluid pb-30" data-wow-delay="0">
        @if(isset($fcontents) && sizeof ($fcontents) > '0')
            @foreach($fcontents as $fcontent)
                <div class="row align-items-center">
                    <div class="col-12 mb-30">
                        <div class="footer-bottom"></div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <p class="font-sm mb-0">&copy; 2023, <strong class="text-brand">Kwikcaart</strong> -All
                            rights reserved</p>
                    </div>
                    <div class="col-xl-4 col-lg-6 text-center d-none d-xl-block">
                     
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 text-end d-none d-md-block">
                        <div class="mobile-social-icon">
                            <h6>Follow Us</h6>
                            <a href="{{ $fcontent->facebook }}"><img
                                    src="{{ asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg') }}"
                                    alt="" /></a>
                            <a href="{{ $fcontent->twitter }}"><img
                                    src="{{ asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg') }}"
                                    alt="" /></a>
                            <a href="{{ $fcontent->instagram }}"><img
                                    src="{{ asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg') }}"
                                    alt="" /></a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    
    

</footer>
