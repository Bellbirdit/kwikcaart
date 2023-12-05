

<div class="modal fade custom-modal" id="onloadModal" tabindex="-1" aria-labelledby="onloadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        
        
        <div class="modal-content">
            
             <i class="fa fa-window-close" aria-hidden="true" style="    position: absolute;    top: 10px;    right: 10px;">close</i>
            <div class="modal-body text-center">
                <div class="">
                    <div class="col-md-12">
                        <h6 class="py-2">Auto Select the Nearest Store??????? </h6>
                        <button type="button" id="btnAction" onClick="locate()" class="btn mt-3 location-btn "><img
                                class="mx-2 "
                                src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}"
                                alt="" />Nearest Stores
                        </button>
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
                        <div class=" my-3">
                            <div class="location-select">
                                <select class=" js-example-basic-single py-1 change_emirate" name="state">
                                    <option value="AL">Select Your Emirates</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->emirate }}">{{ $store->emirate }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" my-3">
                            <div class="location-select">
                                <select class="js-example-basic-single py-1 class select_store" name="state">
                                    <option value="AL">Select Nearest Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->code }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="header-area header-style-1 header-height-2">
    <div class="mobile-promotion">
        <span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>
    </div>
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-4 col-lg-4">
                    <div class="header-info">
                        <ul>
                            <li>Need help? Call Us: <strong> 800 Safeer Market</strong></li>
                            <li><a href="{{url('track-order')}}">Order Tracking</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-xl-2 col-lg-2">


                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="header-info header-info-right">
                        @if(!Auth::check())
                            <ul>
                                <li><a href="#">Express Delivery</a></li>
                                <li><a href="#"> Order & Collect</a></li>
                                <li><a href="#">Home Delivery</a></li>
                                <li><a href="{{ url('/login') }}" class="lable">Login</a>
                                </li>
                                <li><a href="{{ url('/register') }}">Registration</a></li>
                            </ul>
                        @else
                            <ul>
                                <li><a href="#">Express Delivery</a></li>
                                <li><a href="#"> Order & Collect</a></li>
                                <li><a href="#">Home Delivery</a></li>
                                @if(Auth::user()->type == '1')
                                    <li><a href="/dashboard">Dashboard</a></li>
                                    @elseif(Auth::user()->type == '2')
                                    <li><a href="/dashboard">Dashboard</a></li>
                                    @elseif(Auth::user()->hasRole('User'))
                                    <li><a href="/user/dashboard">Dashboard</a></li>
                                    @endif

                                    <li><a href="{{ url('/login') }}">Logout</a></li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle py-1 d-none d-lg-block">
        <div class="container-fluid">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="{{url('/')}}"><img src="{{ asset('assets/imgs/theme/logo.png') }}"
                            alt="logo" /></a>
                </div>
                <div class="header-right">
                     <div class="search-style-2">
                        <form>
                            <input type="text" id="search" name="search" placeholder="Search for products">
                           
                        </form>
                        <div id="search-results" style="height:30px; width:70%;"></div>
                    </div>
                    <div class="header-action-right">
                        <div class="header-action-2">

                            @php
                                $store_id = Session::get('store_id');
                                if (isset($store_id)) {
                                $store_name = App\Models\User::where('code', $store_id)->pluck('name')->first();
                                } else {
                                $store_name = "Select Store ";
                                }
                            @endphp
                            <div class="hotline d-none d-lg-flex  " id="dropdownMenuLink" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <img src="{{ asset('frontend/assets/imgs/theme/icons/icon-location.svg') }}"
                                    alt="" />
                                <p class="py-2"><span>{{ $store_name }} <i class="fi-rs-angle-down"></i></span></p>
                            </div>
                            <div class="dropdown-menu location-div" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#onloadModal">Change Store</a>
                            </div>
                            @if(auth::check())
                                <div class="header-action-icon-2">
                                    <a href="/user/dashboard">
                                        @php
                                            $wishlist = 0;
                                            if ($wishlist > 0) {
                                            $wishlist = App\Models\Wishlist::where('user_id', Auth::id())->count();
                                            } else {
                                            $wishlist = 0;
                                            }
                                        @endphp
                                        <img class="svgInject img-white" alt="Nest"
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-heart.svg') }}" />
                                        <span class="pro-count blue">{{ $wishlist }}</span>
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
                                        <span class="pro-count blue cart_count">{{ $count }}</span>
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
    </div>
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container-fluid">
            <div class="header-wrap header-space-between position-relative">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="index.html"><img
                            src="{{ asset('assets/imgs/theme/logo.png') }}"
                            alt="logo" /></a>
                </div>
                
                @php
                     $cats= App\Models\Category::with('cbannercat')->where('order_level','!=','0')->orderBy('order_level','ASC')->get()
                @endphp
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                            <a class="categories-button-active" href="javascript:;">
                                <span class="fi-rs-apps"></span>Categories
                                <i class="fi-rs-angle-down"></i>
                            </a>
                            <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                                <div class="d-flex categori-dropdown-inner">
                                    <ul>
                                        @foreach($cats as $cat)
                                        @php
                                        $img = $cat->getImage($cat->icon);
                                        @endphp
                                        <li>
                                            <a href="{{route('cat-products',$cat->id)}}"> <img src="{{asset('/uploads/files/' . $img) }}" alt="" />{{$cat->name}}</a>
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
                                <div class="more_categories my-2"><span class="icon"></span> <span class="heading-sm-1">Show more...</span></div>
                            </div>
                        </div>
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                        <nav>
                            <ul>
                                <!--<li class="position-static">-->
                                <!--    <a class="" level="" id="" href="javascript:;">-->
                                <!--        <span class="fi-rs-apps px-2"></span>Categories-->
                                <!--        <i class="fi-rs-angle-down"></i>-->
                                <!--    </a>-->
                                    @php
                                         $cats= App\Models\Category::with('cbannercat')->where('order_level','!=','0')->orderBy('order_level','ASC')->get()
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
                                            <a href="{{route('cat-products',$cat->id)}}" id="{{ $cat->id }}">{{ $cat->name }} </a>

                                            <ul class="mega-menu ">
                                                <li class="sub-mega-menu w-100">
                                                    @php
                                                        $subcats =
                                                        App\Models\Category::where('parent_id',$cat->id)->get(); 
                                                    @endphp
                                                 <div class="row">
                                                     @foreach($subcats as $subca)
                                                            <div class=" col-md-2 pb-3" >
                                                                <a class="d-flex"
                                                                    href="javascript:">{{ $subca->name }}</a>

                                                                <ul>
                                                                    @php
                                                                        $subsub =
                                                                        App\Models\Category::where('parent_id',$subca->id)->get();
                                                                    @endphp
                                                                  
                                                                        @foreach($subsub as $subsu)
                                                                            <li class="menu-title"><a
                                                                                    href="{{ route('cat-products',$cat->id) }}">
                                                                                    {{ $subsu->name }}</a></li>
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


                <div class="header-action-icon-2 d-block d-lg-none">
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
                <div class="header-action-right d-block d-lg-none">
                    <div class="header-action-2">
                        <div class="header-action-icon-2">
                            <a href="shop-wishlist.html">
                                <img alt="Nest"
                                    src="{{ asset('frontend/assets/imgs/theme/icons/icon-heart.svg') }}" />
                                <span class="pro-count white">4</span>
                            </a>
                        </div>
                        <div class="header-action-icon-2">
                            <a class="mini-cart-icon" href="#">
                                <img alt="Nest"
                                    src="{{ asset('frontend/assets/imgs/theme/icons/icon-cart.svg') }}" />
                                <span class="pro-count white">2</span>
                            </a>
                            <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                <ul>
                                    <li>
                                        <div class="shopping-cart-img">
                                            <a href="#"><img alt="Nest"
                                                    src="{{ asset('frontend/assets/imgs/shop/thumbnail-3.jpg') }}" /></a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="#">Plain Striola Shirts</a></h4>
                                            <h3><span>1 × </span>$800.00</h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#"><i class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="shopping-cart-img">
                                            <a href="#"><img alt="Nest"
                                                    src="{{ asset('frontend/assets/imgs/shop/thumbnail-4.jpg') }}" /></a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="#">Macbook Pro 2022</a></h4>
                                            <h3><span>1 × </span>$3500.00</h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#"><i class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="shopping-cart-footer">
                                    <div class="shopping-cart-total">
                                        <h4>Total <span>$383.00</span></h4>
                                    </div>
                                    <div class="shopping-cart-button">
                                        <a href="shop-cart.html">View cart</a>
                                        <a href="shop-checkout.html">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>



<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype"
    async>
</script>
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
        $.get("{{ route('home') }}", {
            store_id: store_id
        }, function (data) {
            console.log(data)
            location.reload();
        });
    });


    $(document).on('click', '.click_here', function () {
        var store_id = $(this).attr('id');
        $.get("{{ route('home') }}", {
            store_id: store_id
        }, function (data) {
            console.log(data)
            location.reload();
        });
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
                            results += '<a href="/product/detail/' + product.id + '" class="bg-white">' + product.name + '</a>';
                            results += '</div>';
                        });
                        $('#search-results').html(results);
                    }
                });
            }
            else{
                $('#search-results').html('');
            }
        });
    });


</script>
