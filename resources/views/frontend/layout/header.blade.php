
<style>
    
    .header-info > ul > li a {
        font-size:12px !important;
    }
    
    
    .header-info > ul > li {
         font-size:12px !important;
    }
    
    
</style>
<div class="modal fade custom-modal " id="onloadModal" tabindex="-1" aria-labelledby="onloadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <i class="fa fa-window-close" aria-hidden="true" style="position: absolute;    top: 0;    right: 0; font-size:40px"></i>
            <div class="modal-body text-center">
                <div class="">
                   
                    <div class="col-md-12">
                        <h6 class="py-2">Auto Select the Nearest Store</h6>
                        <button type="button" id="btnAction" onClick="locate()" class="btn mt-3 location-btn "><img src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}" alt="" />Nearest Stores</button>
                    </div>
                    <div class="row ">
                        <h4 class="pb-3">Your Nearest Stores</h4>
                        <div class="col-md-12 ">
                            <div class="row Processing" style="display:none">
                                <div class="col-lg-12 tet-center ">
                                    <span class="text-success fs-5"> Finding Nearest Stores....</span>
                                </div>
                            </div>
                            <div class="row append_here">
                            </div>
                        </div>
                    </div>
                    <hr>
                    @php
                    $result=App\Models\User::query();
                    $result = $result->whereHas('roles',function($q){
                    $q->where('name','Store');
                    });
                    $storess = $result->orderBy('id','DESC')->get();
                    $stores = $storess->unique('emirate');
                    @endphp
                    <div class="col-md-12">
                        <h6 class="py-2">Select Store Manually </h6>
                        <div class="row">
                        <div class=" my-3 col-sm-12">
                            <div class="location-select">
                                <select class="js-example-basic-single py-1 change_emirate" name="state">
                                    <option value="AL">Select Your Emirates</option>
                                    @foreach($stores as $store)
                                    <option value="{{ $store->emirate }}">{{ $store->emirate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!--<div class=" my-3 col-sm-6">-->
                        <!--    <div class="location-select">-->
                        <!--        <select class="js-example-basic-single py-1 select_store" name="state">-->
                        <!--            <option value="AL">Select Nearest Store</option>-->
                        <!--            @foreach($stores as $store)-->
                        <!--            <option value="{{ $store->code }}">{{ $store->name }}</option>-->
                        <!--            @endforeach-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--</div>-->
                        </div>
                        <div class="row select_store">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="header-area header-style-1 header-height-2">
    <!-- <div class="mobile-promotion">
        <span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>
    </div> -->
    
    <div class="header-top header-top-ptb-1  d-lg-block">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-3 col-lg-3 d-none d-lg-block">
                    <div class="header-info">
                        <ul>  
                            
                               
                            @php
                                $fcontents=App\Models\FooterSetting::all();
                            @endphp
                            @if(isset($fcontents) && sizeof ($fcontents) > '0')
                                @foreach($fcontents as $fcontent)
                                <li>Need help? Call Us&nbsp&nbsp<strong> {{ $fcontent->phone }} </strong></li>
                                @endforeach
                             @else
                                <li>Need help? Call Us&nbsp&nbsp<strong> 600522274 </strong></li>
                            @endif
                            <!--<li><a href="{{url('user/dashboard')}}">Order Tracking</a></li>-->
                        </ul>

                    </div>
                </div>
                <div class="col-9 col-lg-6 col-sm-8 text-center">
                            @php
                                $store_id = Session::get('store_id');
                                 $timing = App\Models\User::where('code',$store_id)->pluck('timing')->first(); 
                                if (isset($store_id)) {
                                $store_name = App\Models\User::where('code', $store_id)->pluck('name')->first();
                                $storecontact = App\Models\User::where('code', $store_id)->pluck('contact')->first();
                                } else {
                                $store_name = "Select Store ";
                                $storecontact = 600522274;
                                }
                               
                            @endphp
                            
                            <div class="hotline"  id="dropdownMenuLink" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <!--<img src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}"-->
                                <!--    alt="" />-->
                                <p class="py-2"><span>{{ $store_name }} | 
                                    {{$storecontact}}
                                    | {{$timing}}</span> <a class="dropdown-item111" href="#" data-bs-toggle="modal"
                                            data-bs-target="#onloadModal">Select Store</a>
                                </p>
                                
                            <!--      <div class="dropdown-menu location-div" aria-labelledby="dropdownMenuLink">-->
                            <!--    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"-->
                            <!--            data-bs-target="#onloadModal">Change Store</a></li>-->
                            <!--</div>-->
                            
                            </div>
                          
                    <!-- @if(Auth::check())-->
                     
                    <!--<h6 class="text-white " style="font-style: italic;"> Hi {{ Auth::user()->name}} Welcome to kwikcaart.com your favorite products at best price</h6>-->
                    <!--@else-->
                    <!--<h6 class="text-white " style="font-style: italic;">Welcome to Kwikcaart your favorite products at best price</h6>-->
                    <!--@endif-->
                </div>
                <div class="col-xl-3 col-lg-3 col-sm-2">
                    <div class="header-info header-info-right">
                      
                            <ul>
                                @php
                                    $headercontents=$fcontents;
                                @endphp
                                @if(isset($headercontents) && sizeof ($headercontents) > '0')
                                @foreach($headercontents as $heacontent)
                                <!--<li><a href="{{$heacontent->express_delivery}}">Express Delivery</a></li>-->
                                <!--<li><a href="{{$heacontent->order_collect}}">Order & Collect</a></li>-->
                                <!--<li><a href="{{$heacontent->home_delivery}}">Home Delivery</a></li>-->
                                @endforeach
                                 @endif
                                 @if(!Auth::check())
                                <li><a href="{{ url('/login') }}" class="lable">Login</a>
                                        
                                
                                </li>
                                <li><a href="{{ url('/register') }}">Registration</a></li>
                                 @else
                                 @if(Auth::user()->type == '1')
                                    <li><a href="/dashboard">Dashboard</a></li>
                                    @elseif(Auth::user()->type == '2')
                                    <li><a href="/dashboard">{{Auth::user()->name;}}</a></li>
                                    @elseif(Auth::user()->hasRole('User'))
                                    <li class="dpd"><a href="/user/dashboard" class="lable">Welcome to, {{Auth::user()->name;}} <i class="fas fa-angle-down"></i></a>
                                    
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                        <ul>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard"><i class="fi fi-rs-user mr-10"></i>My Account</a>
                                            </li>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard?tab=profile"><i class="fi fi-rs-location-alt mr-10"></i>Profile</a>
                                            </li>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard?tab=wishlist"><i class="fi fi-rs-label mr-10"></i>Wishlist</a>
                                            </li>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard?tab=track_your_order"><i class="fi fi-rs-heart mr-10"></i>Track Your Order</a>
                                            </li>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard?tab=support_ticket"><i class="fi fi-rs-settings-sliders mr-10"></i>Support Chat</a>
                                            </li>
                                            <li>
                                                <a href="https://kwikcaart.com/user/dashboard?tab=order_feedback"><i class="fi fi-rs-settings-sliders mr-10"></i>Order Feedback</a>
                                            </li>
                                            <li>
                                                <a href="{{ url('/login') }}"><i class="fi fi-rs-sign-out mr-10"></i>Sign out</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    </li>
                                    @endif
                                    <li><a href="{{ url('/login') }}">Logout</a></li>
                                @endif
                            </ul>
                        
                            
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="header-middle py-1  d-lg-block">
        <div class="container-fluid">
            <div class="header-wrap">
                <div class="logo logo-width-1 d-lg-block d-none">
                    <a href="{{url('/')}}"><img src="{{ asset('assets/imgs/theme/logo.png') }}"
                            alt="logo" /></a>
                </div>
                <div class="header-right">
                     <div class="search-style-2 d-lg-block d-none ">
                        <form>
                            <input type="text" id="search" name="search" placeholder="Search for products">
                           <div id="search-results"></div>
                        </form>
                        
                        
                    </div>
                    <div class="header-action-right">
                        <div class="d-flex header-action-2">
                            @php
                                $setting=App\Models\WebSetting::first();
                            @endphp
                            <a href=""><img src="{{ asset('frontend/assets/imgs/theme/icons/fast-delivery.png') }}"alt="" style="width: 60px;" /></a>
                            <div class="px-3">
                                <h6 class="m-0 ">Free Delivery </h6>
                                <small class="m-0 ">Above {{ $setting->delivery_applicable??0 }} AED</small>
                            </div>
                        </div>
                        <div class="header-action-2">

                            
                            @if(auth::check())
                                <div class="header-action-icon-2">
                                    <a href="/user/dashboard">
                                        @php
                                            
                                            
                                           
                                            $wishlist = App\Models\WishList::where('user_id', Auth::id())->count();

                                        @endphp
                                        <img class="svgInject img-white1" alt="Nest"
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-heart.svg') }}" />
                                        @if($wishlist)
                                        <span class="pro-count blue">{{ $wishlist }}</span>
                                        @else
                                        <span class="pro-count blue">0</span>
                                        @endif
                                    </a>
                                    <a href="/user/dashboard?tab=wishlist"><span class="lable">Wishlist</span></a>
                                </div>
                                <div class="header-action-icon-2">
                                    <a class="mini-cart-icon" href="javascript:;">
                                        <img class="img-white1"
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-cart.svg') }}" />
                                        <?php 
                                        $storeproducts = App\Models\StoreProducts::where('store_id', Session::get('store_id'))->where('stock', 'yes')->pluck('product_id');
                                            $count=App\Models\Cart::where('user_id',auth()->user()->id)->whereIn('product_id', $storeproducts)->where('store_id', Session::get('store_id'))->where('status','pending')->count(); 
                                        ?>
                                  
                                        <span class="pro-count blue cart_count" id="cart_count">{{$count }}</span>
                                      
                                    </a>
                                    <a href="javascript:;"><span class="lable">Cart</span></a>
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2 pr-5">
                                        <ul class="append_cart">
                                        <?php  
                                            $carts= App\Models\Cart::where('user_id',auth()->user()->id)->whereIn('product_id', $storeproducts)->where('store_id', Session::get('store_id'))->where('status','pending')->get();
                                            $total = 0;
                                            ?>

                                            @if(isset($carts) && sizeof($carts) > 0)
                                                @foreach($carts as $cart)

                                                    <?php  
                                                        $product = App\Models\Product::where('id', $cart->product_id)->first();
                                                        $priceArray = $product->get_deal_price();
                                                        $total += $priceArray['price']*$cart->quantity;
                                                    ?>
                                                    <li id="remove{{ $cart->id }}">
                                                        <div class="row">
                                                            
                                                            <div class="col-md-3">
                                                                <div class="shopping-cart-img">
                                                                    <a href="javascript;:"><img alt="" src="{{ asset('uploads/files/'.$cart->image) }}" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="shopping-cart-title">
                                                                    <p><a href="javascript;:">{{ $cart->name }}</a><p>
                                                                    <h4><span>{{ $cart->quantity }} × </span>{{ round($priceArray['price'],2) }}</h4>
                                                                </div>
                                                            </div>
                                                            <div style="    position: absolute;    right: 10px;    width: 26px; ">
                                                                <a href="javascript:;" id="{{ $cart->id }}" class="remove"><i class="fi-rs-cross-small"></i></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <hr style="margin:0 !important">
                                                @endforeach
                                            @else
                                                <li class="text-center" style="justify-content: center;"><span
                                                        class="text-danger" style="font-size: 15px;">Your cart is
                                                        empty</span></li>
                                            @endif
                                        </ul>

                                       <div class="shopping-cart-footer">
                                            <div class="shopping-cart-total">
                                                <h4>Total <span class="total" id="total">AED{{ round($total,2) }}</span></h4>
                                            </div>
                                            <div class="shopping-cart-button">
                                                <a href="/cart" class="outline">View cart</a>
                                                <a href="/checkout">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 ">
    @if(session('error'))
        <div class="alert alert-danger">
            <p class="text-center" style="color: black; font-weight: bold;">{{ session('error') }}</p>
            {{ session()->forget('error') }}
        </div>
    @endif
</div>

    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container-fluid">
            <div class="header-wrap header-space-between position-relative">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="{{url('/')}}"><img
                            src="{{ asset('assets/imgs/theme/logo.png') }}"
                            alt="logo" /></a>
                </div>
                
                @php
                     $cats= App\Models\Category::where('top','!=','0')->orderBy('top','ASC')->take('13')->get();
                     $cats2= App\Models\Category::where('top','!=','0')->orderBy('top','DESC')->take('13')->get();
                @endphp
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                            <a class="categories-button-active" href="javascript:;" >
                                <span class="fi-rs-apps"></span><span style="font-size:12px !important; color: #253D4E;">Categories</span>
                                <i class="fi-rs-angle-down"></i>
                            </a>
                            <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading" style="min-width:260px !important">
                                <div class="d-flex categori-dropdown-inner" >
                                    <ul >
                                        @foreach($cats as $cat)
                                        @php
                                        $img = $cat->getImage($cat->icon);
                                        @endphp
                                        <li >
                                            <a href="{{route('cat-products',$cat->id)}}"> {{$cat->name}}</a>
                                        </li>
                                        @endforeach
                                       
                                    </ul>
                                    <ul>
                                        @foreach($cats2 as $cat2)
                                        @php
                                        $img = $cat2->getImage($cat2->icon);
                                        @endphp
                                        <li>
                                            <a href="{{route('cat-products',$cat2->id)}}"> {{$cat2->name}}</a>
                                        </li>
                                        @endforeach
                                       
                                    </ul>
                                </div>
                                <!--<div class="more_slide_open" style="display: none">-->
                                <!--    <div class="d-flex categori-dropdown-inner">-->
                                <!--        <ul>-->
                                <!--            <li>-->
                                <!--                <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/icon-1.svg" alt="" />Milks and Dairies</a>-->
                                <!--            </li>-->
                                <!--            <li>-->
                                <!--                <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/icon-2.svg" alt="" />Clothing & beauty</a>-->
                                <!--            </li>-->
                                <!--            <li>-->
                                <!--                <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/icon-3.svg" alt="" />Wines & Drinks</a>-->
                                <!--            </li>-->
                                <!--            <li>-->
                                <!--                <a href="shop-grid-right.html"> <img src="assets/imgs/theme/icons/icon-4.svg" alt="" />Fresh Seafood</a>-->
                                <!--            </li>-->
                                <!--        </ul>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="more_categories my-2"><span class="icon"></span> <span class="heading-sm-1"><a href="{{url('categories')}}">Show more...</a></span></div>-->

                            </div>
                        </div>
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2  d-lg-block font-heading">
                        <nav>
                            <ul>
                                <!--<li class="position-static">-->
                                <!--    <a class="" level="" id="" href="javascript:;">-->
                                <!--        <span class="fi-rs-apps px-2"></span>Categories-->
                                <!--        <i class="fi-rs-angle-down"></i>-->
                                <!--    </a>-->
                                    @php
                                         $cats= App\Models\Category::with('cbannercat')->where('order_level','!=','0')->orderBy('order_level','ASC')->get();
                                         
                                    @endphp
                                    <!--<ul class="mega-menu row">-->
                                    <!--    <div class="tab big-menu">-->
                                            @foreach($cats as $cat)
                                            @php
                                            $img = $cat->getImage($cat->icon);
                                            @endphp
                                            <!--<a href="#" class="tablinks" id="{{$cat->id}}">-->
                                            <!--<div class="d-flex">-->
                                            <!--    <img src="{{asset('/uploads/files/' . $img) }}" alt="" width="50px" />-->
                                                <!--{{$cat->name}}-->
                                            <!--</div>-->
                                            <!--</a>-->
                                            
                                            @endforeach
                                <!--        </div>-->
                                <!--        <div  class="tabcontent" style="display:block">-->
                                <!--            <div id="catLoader" class="text-center" style="display:none;">-->
                                <!--                <p  class="spinner-border text-success"></p>-->
                                <!--            </div>-->
                                <!--            <div class="row" id="subcat_div">-->

                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </ul>-->
                                <!--</li>-->
                                      @if(isset($cats) && sizeof($cats)>0)
                                    @foreach($cats as $cat)
                                        <li class="position-static">
                                            <a href="javascript:;" id="{{ $cat->id }}">{{ $cat->name }} </a>

                                            <ul class="mega-menu ">
                                                <li class="sub-mega-menu w-100">
                                                    @php
                                                        $subcats = $cat->cbannercat; 
                                                    @endphp
                                                 <div class="row">
                                                     @foreach($subcats as $subca)
                                                            <div class=" col-md-2 pb-3" >
                                                                <a class="d-flex"
                                                                    href="{{ route('cat-products',$subca->id) }}">{{ $subca->name }}</a>

                                                                <ul>
                                                                    @php
                                                                        $subsubb = $subca->cbannercat;
                                                                        $subsub=$subsubb->unique('name') ;
                                                                       
                                                                    @endphp
                                                                  
                                                                        @foreach($subsub as $subsu)
                                                                            <li class="menu-title"><a
                                                                            href="{{ route('cat-products',$subsu->id) }}">{{ $subsu->name }}</a></li>
                                                                                
                                                                               
                                                                                  @php
                                                                                    $subsuuu = $subsu->cbannercat;
                                                                                     $subsuu=$subsuuu->unique('name');
                                                                                @endphp
                                                                             
                                                                                    @foreach($subsuu as $su)

                                                                                        <li class="menu-title"><a href="{{ route('cat-products',$su->id) }}">
                                                                                                {{ $su->name }}</a></li>
                                                                                    @endforeach
                                                                               
                                                                        @endforeach
                                                                  
                                                                </ul>
                                                            </div>
                                                        @endforeach
                                                 </div>
                                                        
                                                   
                                                </li>

                                            </ul>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
                        </nav>
                    </div>
                </div>
                        <!--mobile header-->

                 <div class="header-action-icon-2 d-block d-lg-none">
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
                <div class="header-action-right d-block d-lg-none">
                   <div class="header-action-2">

                            @php
                                $store_id = Session::get('store_id');
                                 $timing = App\Models\User::where('code',$store_id)->pluck('timing')->first(); 
                                if (isset($store_id)) {
                                $store_name = App\Models\User::where('code', $store_id)->pluck('name')->first();
                                $storecontact = App\Models\User::where('code', $store_id)->pluck('contact')->first();
                                } else {
                                $store_name = "Select Store ";
                                $storecontact = 600522274;
                                }
                               
                            @endphp
                            
                            <div class="hotline d-none d-lg-flex  " id="dropdownMenuLink" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}"
                                    alt="" />
                                <p class="py-2"><span>{{ $store_name }}<br>
                                {{$storecontact}}
                                <br> {{$timing}}<i class="fi-rs-angle-down"></i></span></p>
                            
                            </div>
                            <div class="dropdown-menu location-div" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#onloadModal">Change Store</a></li>
                            </div>
                            @if(auth::check())
                                <div class="header-action-icon-2">
                                    <a href="/user/dashboard">
                                        @php
                                            
                                            
                                           
                                            $wishlist = App\Models\WishList::where('user_id', Auth::id())->count();

                                        @endphp
                                        <img class="svgInject img-white" alt="Nest"
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-heart.svg') }}" />
                                        @if($wishlist)
                                        <span class="pro-count blue">{{ $wishlist }}</span>
                                        @else
                                        <span class="pro-count blue">0</span>
                                        @endif
                                    </a>
                                    <a href="/user/dashboard"><span class="lable">Wishlist</span></a>
                                </div>
                                <div class="header-action-icon-2">
                                    <a class="mini-cart-icon" href="javascript:;">
                                        <img class="img-white"
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-cart.svg') }}" />
                                        <?php 
                                            $count=App\Models\Cart::where('user_id',auth()->user()->id)->where('store_id', Session::get('store_id'))->where('status','pending')->count(); 
                                        ?>
                                        <span class="pro-count blue cart_count"id="cart_count">{{ $count }}</span>
                                    </a>
                                    <a href="javascript:;"><span class="lable">Cart</span></a>
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2 pr-5">
                                        <ul class="append_cart">
                                        <?php  
                                            $carts= App\Models\Cart::where('user_id',auth()->user()->id)->where('store_id', Session::get('store_id'))->where('status','pending')->get();

                                            ?>

                                            @if(isset($carts) && sizeof($carts) > 0)
                                                @foreach($carts as $cart)

                                                    <?php  ?>
                                                    <li id="remove{{ $cart->id }}">
                                                        <div class="row">
                                                            <div class="shopping-cart-delete px-4">
                                                                <a href="javascript:;" id="{{ $cart->id }}" class="remove"><i class="fi-rs-cross-small"></i></a>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="shopping-cart-img">
                                                                    <a href="javascript;:"><img alt="" src="{{ asset('uploads/files/'.$cart->image) }}" /></a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="shopping-cart-title">
                                                                    <p><a href="javascript;:">{{ $cart->name }}</a><p>
                                                                    <h4><span>{{ $cart->quantity }} × </span>{{ round($cart->price,2) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <hr>
                                                @endforeach
                                            @else
                                                <li class="text-center" style="justify-content: center;"><span
                                                        class="text-danger" style="font-size: 15px;">Your cart is
                                                        empty</span></li>
                                            @endif
                                        </ul>

                                        <div class="shopping-cart-footer">
                                            <div class="shopping-cart-total">
                                                @php
                                                    $total=App\Models\Cart::where('user_id',auth()->user()->id)->where('store_id', Session::get('store_id'))->where('status','pending')->sum('quantity_price');
                                                @endphp
                                                <h4>Total <span class="total">AED{{ round($total,2) }}</span></h4>
                                            </div>
                                            <div class="shopping-cart-button">
                                                <a href="/cart" class="outline">View cart</a>
                                                <a href="/checkout">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
</header>

@if(!stristr(request()->url(), "/checkout"))
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype&libraries=places&v=weekly"
    async>
</script>
@endif
<style>
    /* Customize the scrollbar thumb (handle) */
    #search-results::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the thumb */
            border-radius: 6px; 
    }
    
    /* Customize the scrollbar track */
    #search-results::-webkit-scrollbar-track {
        background: #fff;
    }
    #search-results::-webkit-scrollbar {
            width: 12px; /* Width of the scrollbar */
        }
        #search-results{
            position: absolute;
    max-height: 300px;
    overflow-y: scroll;
    z-index: 9;
    top: 40px;
    /*border: 2px solid black;*/
    border-radius: 10px;
    /*padding: 12px;*/
    background: white;
        }
        .result.bg-white {
    padding: 5px;
}

.accordion-button{
        background-color: white;
}
.accordion-button > .card-header{
    color: black;
}
.accordion-button:not(.collapsed) {
    background-color: white;
    color: black;
}
.accordion{
    margin-bottom: 2px;
}

</style>
<script>
    $(document).ready(function () {
        $('#onloadModal').modal({
            backdrop: 'static',
            keyboard: false
        })
    });
    
   
</script>
<script>
    $(document).on('change', '.select_store', function () {

        var store_id = $('.select_store option:selected').val();
        window.location.href = "{{ route('home') }}?store_id="+store_id;
        // $.get("{{ route('home') }}", {
        //     store_id: store_id
        // }, function (data) {
        //     console.log(data)
        //     location.reload();
        // });
    });


    $(document).on('click', '.click_here', function () {
        var store_id = $(this).attr('id');
        window.location.href = "{{ route('home') }}?store_id="+store_id;
        // $.get("{{ route('home') }}", {
        //     store_id: store_id
        // }, function (data) {
        //     console.log(data)
        //     location.reload();
        // });
    });

</script>

<script type="text/javascript">
    var map;

    function initMap() {
        var mapLayer = document.getElementById("map-layer");
        var centerCoordinates = new google.maps.LatLng(37.6, -95.665);
        var defaultOptions = {
            center: centerCoordinates,
            zoom: 4
        }

        map = new google.maps.Map(mapLayer, defaultOptions);
    }

    function locate() {

        $('.Processing').css('display', 'inline-block')
        $(".append_here").html('')

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var currentLatitude = position.coords.latitude;
                var currentLongitude = position.coords.longitude;

                var infoWindowHTML = "Latitude: " + currentLatitude + "<br>Longitude: " + currentLongitude;
                var infoWindow = new google.maps.InfoWindow({
                    map: map,
                    content: infoWindowHTML
                });
                var currentLocation = {
                    lat: currentLatitude,
                    lng: currentLongitude
                };
                console.log(currentLatitude)
                $.ajax({
                    url: "/near/store",
                    type: "get",
                    data: {
                        currentLatitude: currentLatitude,
                        currentLongitude: currentLongitude
                    },
                    dataType: "JSON",
                    cache: false,

                    success: function (response) {
                        console.log(response);
                        if (response["status"] == "fail") {

                            $('.Processing').css('display', 'none')


                        } else if (response["status"] ==
                            "success") {
                            $('.Processing').css('display', 'none')


                            $(".append_here").html(response['html'])


                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                infoWindow.setPosition(currentLocation);
            });

        }
    }

</script>
    <script>
    $(document).ready(function(){
        $('#search').on('keyup', function(){
            var query = $(this).val();
            if(query.length >= 3){
                $.ajax({
                    url: "{{ route('product.auto-search') }}",
                    type: "GET",
                    data: {query:query},
                    dataType: "json",
                    success:function(data){
                        var results = '';
                        $.each(data, function(key, product){
                            results += '<div class="result bg-white">';
                            results += '<a href="/product/detail/' + product.slug + '" class="bg-white">' + product.name + '</a>';
                            results += '</div>';
                        });
                        $('#search-results').html(results);
                        if(results != ''){
                            $('#search-results').css('border', '2px solid black');
                        $('#search-results').css('padding', '12px');    
                        }else{
                            $('#search-results').css('border', 'inherit');
                            $('#search-results').css('padding', '0px');
                        }
                        
                    }
                });
            }
            else{
                $('#search-results').html('');
                $('#search-results').css('border', 'inherit');
                        $('#search-results').css('padding', '0px');
            }
        });
    });

    $(document).on("click", function(event) {
        // Check if the clicked element is not inside the #myDiv
        if (!$(event.target).closest("#search-results").length) {
            // Click occurred outside #myDiv
            // You can perform your actions here
            $('#search-results').css('border', 'inherit');
                        $('#search-results').css('padding', '0px');
            $('#search-results').html('');
        }
        if (!$(event.target).closest("#onloadModal").length) {
            // Click occurred outside #myDiv
            // You can perform your actions here
            $("#onloadModal").modal('hide');
        }
        
    });
</script>
