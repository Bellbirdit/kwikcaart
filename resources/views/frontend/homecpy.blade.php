@extends('frontend/layout/master')
@section('title')
Kwikcaart | Homee
@endsection
@section('frontend/content')
<style>
    .catscroll {
        -ms-overflow-style: none;
        /* Internet Explorer 10+ */
        scrollbar-width: none;
        /* Firefox */
    }

    .catscroll::-webkit-scrollbar {
        display: none;
        /* Safari and Chrome */
    }

</style>


<main class="main ">
    <section class="home-slider position-relative mb-30 px-3">
        <div class="container">
            <div class="home-slide-cover mt-3">
                <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                    <div class="single-hero-slider single-animation-wrap"
                        style="background-image: url({{ asset('frontend/assets/imgs/slider/slider-1.png') }}">
                        <div class="slider-content">
                            <h1 class="display-2 mb-40">
                                Don’t miss amazing<br />
                                grocery deals
                            </h1>
                            <p class="mb-65">Sign up for the daily newsletter</p>
                            <form class="form-subcriber d-flex">
                                <input type="email" placeholder="Your emaill address" />
                                <button class="btn" type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                    <div class="single-hero-slider single-animation-wrap"
                        style="background-image: url({{ asset('frontend/assets/imgs/slider/slider-2.png') }}">
                        <div class="slider-content">
                            <h1 class="display-2 mb-40">
                                Fresh Vegetables<br />
                                Big discount
                            </h1>
                            <p class="mb-65">Save up to 50% off on your first order</p>
                            <form class="form-subcriber d-flex">
                                <input type="email" placeholder="Your emaill address" />
                                <button class="btn" type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="slider-arrow hero-slider-1-arrow"></div>
            </div>
        </div>
    </section>
    <!--End hero slider-->
    <section class="popular-categories section-padding px03 px-5">
        <div class="container wow animate__animated animate__fadeIn">
            <div class="section-title">
                <div class="title">
                    <h3>Featured Categories</h3>
                    <ul class="list-inline nav nav-tabs links">
                        @foreach($fsubcategories as $cat)
                            <li class="list-inline-item nav-item"><a class="nav-link select_category"
                                    level="{{ $cat->level }}" id="{{ $cat->id }}"
                                    href="javascript:;">{{ ucwords($cat->name) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row ">
                <span class="append_category d-flex catscroll" style=" width: 100%; overflow: scroll;">
                    @foreach($faeturedcat as $fcat)
                        <?php
                            $img = $fcat->getImage($fcat->icon);
                            $exists = public_path('/uploads/files/' . $img);
                            ?>
                        <div class="col-lg-2">
                            <div class="card-2 bg-9 mx-2 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                                <figure class="img-hover-scale overflow-hidden">
                                    <a href="{{ route('shop',$fcat->id) }}">
                                        @if(file_exists($exists))
                                            <img src="{{ asset('uploads/files/'.$img) }}"
                                                class="kk" alt="">
                                        @else
                                            <img src="{{ asset('uploads/files/no_image.jpg') }}"
                                                alt="">
                                        @endif
                                    </a>
                                </figure>
                                <h6><a
                                        href="{{ route('shop',$fcat->id) }}">{{ ucwords($fcat->name) }}</a>
                                </h6>
                            </div>
                        </div>

                    @endforeach
                </span>

                <div class="col-lg-12 text-center">
                    <div class="text-danger not_found" style="display:none;font-size: 18px;">No data found</div>
                </div>
            </div>
        </div>
    </section>
    <!--End category slider-->

    @if(isset($todays_deal_products) && sizeof($todays_deal_products)>0)
        <section class="section-padding pb-5 px-2">
            <div class="container">
                <div class="section-title">
                    <h3 class="">Deals Of The Day</h3>
                    <a class="show-all" href="javascript:">
                        All Deals
                        <i class="fi-rs-angle-right"></i>
                    </a>
                </div>
                <div class="row">

                    @foreach($todays_deal_products as $pro)
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <div class="product-cart-wrap style-2">
                                <div class="product-img-action-wrap">
                                    <div class="product-img">
                                        <a href="/product/detail/{{ $pro->id }}">
                                            <img src="{{ asset('uploads/files/'.$pro->getImage($pro->thumbnail)) }}"
                                                alt="" />
                                        </a>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" data-countdown="{{ $pro->discount_end_date }}">
                                        </div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><a href="/product/detail/{{ $pro->id }}">{{ $pro->name }}</a></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <!-- <div>
                                                <span class="font-small text-muted">By <a
                                                        >{{ $pro->store->name }}</a></span>
                                            </div> -->

                                        <div class="product-card-bottom">
                                            <div class="product-price">

                                                <span>AED {{ $pro->discount_price($pro) }}</span>
                                                <span class="old-price">${{ $pro->price }}</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add add_cart" href="javascript:;" id="{{ $pro->id }}"><i
                                                        class="fi-rs-shopping-cart mr-5"></i>Add
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif







    @if(isset($storedeals) && sizeof($storedeals)>0)
        <section class="product-tabs section-padding position-relative px-5">
            @foreach($storedeals as $storedeal)
                <div class="container">
                    <div class="section-title">
                        <h3>{{ ucwords($storedeal->title) }}</h3>

                    </div>
                    <div class="row">

                        <?php $storedealsproducts = App\Models\StoredealProduct::where('storedeal_id', $storedeal->id)->get();?>
                        @foreach($storedealsproducts as $store_deal_product)
                            <?php $storeproducts = App\Models\Product::where('id', $store_deal_product->product_id)->get();?>
                            @foreach($storeproducts as $storproduct)
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <div class="product-cart-wrap style-2">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img">
                                                <a href="/product/detail/{{ $storproduct->id }}">
                                                    <img src="{{ asset('uploads/files/'.$storproduct->getImage($storproduct->thumbnail)) }}"
                                                        alt="" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="deals-countdown-wrap">
                                                <div class="deals-countdown"
                                                    data-countdown="{{ $storedeal->end_date }}">
                                                </div>
                                            </div>
                                            <div class="deals-content">
                                                <div class="product-category">
                                                    <a
                                                        href="{{ route('shop',$storproduct->id) }}">{{ $storproduct->category->name }}</a>

                                                </div>
                                                <h2><a
                                                        href="/product/detail/{{ $storproduct->id }}">{{ $storproduct->name }}</a>
                                                </h2>
                                                <div class="product-rate-cover">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: 90%"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> (4.0)</span>
                                                </div>
                                                <!-- <div>
                                                <span class="font-small text-muted">By <a
                                                        >{{ $storproduct->store->name }}</a></span>
                                            </div> -->

                                                <div class="product-card-bottom">
                                                    <div class="product-price">
                                                        <?php $stordeladiscount = $storproduct->price * $storedeal->discount / 100;
                                                            $dealvalues = $storproduct->price - $stordeladiscount?>
                                                        <span>AED {{ $dealvalues }}</span>
                                                        <span class="old-price">${{ $storproduct->price }}</span>
                                                    </div>

                                                    <div class="add-cart">
                                                        <a class="add add_cart" href="javascript:;"
                                                            id="{{ $storproduct->id }}"><i
                                                                class="fi-rs-shopping-cart mr-5"></i>Add
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>
    @endif









    <!-- storewise deals -->
    @if(isset($storedeals) && sizeof($storedeals)>0)

        <section class="product-tabs section-padding position-relative px-5">
            @foreach($storedeals as $storedeal)

                <div class="container">
                    <div class="section-title style-2 wow animate__animated animate__fadeIn">
                        <h3>{{ ucwords($storedeal->title) }}</h3>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                            <div class="row product-grid-4">


                                <?php $storedealsproducts = App\Models\StoredealProduct::where('storedeal_id', $storedeal->id)->get();?>
                                @foreach($storedealsproducts as $store_deal_product)
                                    <?php $storeproducts = App\Models\Product::where('id', $store_deal_product->product_id)->get();?>

                                    @foreach($storeproducts as $storproduct)

                                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn"
                                                data-wow-delay=".1s">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="/product/detail/{{ $storproduct->id }}">
                                                            <img class="default-img"
                                                                src="{{ asset('uploads/files/'.$storproduct->getImage($storproduct->thumbnail)) }}"
                                                                alt="" />
                                                            <img class="hover-img"
                                                                src="{{ asset('uploads/files/'.$storproduct->getImage($storproduct->thumbnail)) }}"
                                                                alt="" />
                                                        </a>
                                                    </div>

                                                    <div
                                                        class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="hot">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <div class="product-category">
                                                        <a
                                                            href="{{ route('shop',$storproduct->id) }}">{{ $storproduct->category->name }}</a>

                                                    </div>
                                                    <h2><a
                                                            href="/product/detail/{{ $storproduct->id }}">{{ $storproduct->name }}</a>
                                                    </h2>
                                                    <div class="product-card-bottom">
                                                        <div class="product-price">
                                                            <?php $stordeladiscount = $storproduct->price * $storedeal->discount / 100;
                                                                $dealvalues = $storproduct->price - $stordeladiscount?>
                                                            <span>AED {{ $dealvalues }}</span>
                                                            <span class="old-price">${{ $storproduct->price }}</span>
                                                        </div>
                                                        <div class="add-cart">
                                                            <a class="add add_cart" href="javascript:;"
                                                                id="{{ $storproduct->id }}"><i
                                                                    class="fi-rs-shopping-cart mr-5"></i>Add
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </section>

    @endif

    <!-- Storewise deal end -->



    @if(isset($deals) && sizeof($deals)>0)

        <section class="product-tabs section-padding position-relative px-5">
            @foreach($deals as $deal)

                <div class="container">
                    <div class="section-title style-2 wow animate__animated animate__fadeIn">
                        <h3>{{ ucwords($deal->title) }}</h3>
                    </div>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                            <div class="row product-grid-4">


                                <?php $deals_product = App\Models\DealProduct::where('deal_id', $deal->id)->get();?>
                                @foreach($deals_product as $deal_product)
                                    <?php $products = App\Models\Product::where('id', $deal_product->product_id)->get();?>

                                    @foreach($products as $product)

                                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn"
                                                data-wow-delay=".1s">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="/product/detail/{{ $product->id }}">
                                                            <img class="default-img"
                                                                src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}"
                                                                alt="" />
                                                            <img class="hover-img"
                                                                src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}"
                                                                alt="" />
                                                        </a>
                                                    </div>

                                                    <div
                                                        class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="hot">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <div class="product-category">
                                                        <a
                                                            href="{{ route('shop',$product->id) }}">{{ $product->category->name }}</a>

                                                    </div>
                                                    <h2><a
                                                            href="/product/detail/{{ $product->id }}">{{ $product->name }}</a>
                                                    </h2>

                                                    <div>
                                                        <span class="font-small text-muted">Orgin <a
                                                                href="vendor-details-1.html">{{ $product->origin }}</a></span>
                                                    </div>
                                                    <div class="product-card-bottom">
                                                        <div class="product-price">
                                                            <?php $deladiscount = $product->price * $deal->discount / 100;
                                                                $dealvalue = $product->price - $deladiscount?>
                                                            <span>AED {{ $dealvalue }}</span>
                                                            <span class="old-price">${{ $product->price }}</span>
                                                        </div>
                                                        <div class="add-cart">
                                                            <a class="add add_cart" href="javascript:;"
                                                                id="{{ $product->id }}"><i
                                                                    class="fi-rs-shopping-cart mr-5"></i>Add
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </section>

    @endif

    <!-- banner -->
    <section class="section-padding mb-10 px-5">
        <img src="https://safeer.bellbirdit.com/frontend/image/adds/safeer-plus.png" class="w-100 bn-shadow" alt="">
    </section>
    <section class="section-padding mb-30 px-5">

<div class="container">
  
        <div class="row">
        @if(isset($subcategories) && sizeof($subcategories) >1)
            @foreach($subcategories as $cat)
            <?php $cat_img = $cat->getImage($cat->banner); ?>
            <div class=" col-lg-6 col-md-6 mb-sm-5 mb-md-0 wow animate__animated animate__fadeInUp"
                data-wow-delay="0">
                <figure class="">
                    <a href="/shop/{{ $cat->id }}"><img class="bn-shadow"
                            src="{{ asset('uploads/files/'.$cat_img) }}" alt="" /></a>
                </figure>
                <div class="swiper mySwiper container">
                    <div class="swiper-wrapper text-center py-3 ">
                        <?php ?>
                        @if(isset($cat->cbannercat) && sizeof($cat->cbannercat)>0)
                            @foreach($cat->cbannercat as $scat)
                                <?php $img = $scat->getImage($scat->icon);?>
                                <div class="swiper-slide sub-cat">
                                    <div>
                                        <a href="/shop/{{ $cat->id }}"> <img
                                                src="{{ asset('uploads/files/'.$img) }}"
                                                alt=""></a>
                                    </div>
                                    <span><a href="/shop/{{ $cat->id }}"> {{ $scat->name }}</a></span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
   
</div>
</section>

</main>
@endsection
@section('scripts')


<script>
    $(document).on('click', '.select_category', function () {
        $(".append_category").html('')
        $('.not_found').css('display', 'none');
        var id = $(this).attr('id');
        var level = $(this).attr('level');
        console.log(level);
        $.ajax({
            url: "/sub/category",
            type: "get",
            data: {
                level: level,
                id: id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    if (response['html'] == '') {
                        $('.not_found').css('display', 'inline-block');

                    } else {
                        $(".append_category").html(response['html'])
                    }
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $(document).on('click', '.select_store', function () {

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

    $(document).on('click', '.add_cart', function () {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        $.ajax({
            url: "/add/cart/" + id,
            type: "get",
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                     toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    $(".append_cart").html(response['html'])
                    toastr.success('Success', response["msg"])
                    $(".total").html(response['total'])
                    $('.cart_count').html(response['cart_count'])

                }
            },
            error: function (error) {
                console.log(error);
            }
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
@endsection
