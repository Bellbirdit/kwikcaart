@extends('frontend/layout/master')
@section('title')
Kwikcaart | Shop
@endsection
@section('frontend/content')

<main class="main">
    <!--<div class="page-header mt-30 mb-50">-->
    <!--    <div class="container">-->
    <!--        <div class="archive-header">-->
    <!--            <div class="row align-items-center">-->
    <!--                <div class="col-xl-3">-->
    <!--                    <h1 class="mb-15">Snack</h1>-->
    <!--                    <div class="breadcrumb">-->
    <!--                        <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>-->
    <!--                        <span></span> Shop <span></span> Snack-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-xl-9 text-end d-none d-xl-block">-->
    <!--                    <ul class="tags-list">-->
    <!--                        <li class="hover-up">-->
    <!--                            <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Cabbage</a>-->
    <!--                        </li>-->
    <!--                        <li class="hover-up active">-->
    <!--                            <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Broccoli</a>-->
    <!--                        </li>-->
    <!--                        <li class="hover-up">-->
    <!--                            <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Artichoke</a>-->
    <!--                        </li>-->
    <!--                        <li class="hover-up">-->
    <!--                            <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Celery</a>-->
    <!--                        </li>-->
    <!--                        <li class="hover-up mr-0">-->
    <!--                            <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Spinach</a>-->
    <!--                        </li>-->
    <!--                    </ul>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="container-fluid mb-30">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                    <!--<div class="totall-product">-->
                    <!--    <p>We found <strong class="text-brand product_count"></strong> items for-->
                    <!--        you!</p>-->
                    <!--</div>-->
                    <!-- <div class="sort-by-product-area">
                        <div class="sort-by-cover mr-10">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps"></i>Show:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> 50 <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <div class="sort-by-dropdown">
                                <ul>
                                    <li><a class="active" href="#">50</a></li>
                                    <li><a href="#">100</a></li>
                                    <li><a href="#">150</a></li>
                                    <li><a href="#">200</a></li>
                                    <li><a href="#">All</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="sort-by-cover">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps-sort"></i>Sort by:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> Featured <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <div class="sort-by-dropdown">
                                <ul>
                                    <li><a class="active" href="#">Featured</a></li>
                                    <li><a href="#">Price: Low to High</a></li>
                                    <li><a href="#">Price: High to Low</a></li>
                                    <li><a href="#">Release Date</a></li>
                                    <li><a href="#">Avg. Rating</a></li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="row product-grid append_here_products ">
                    
                    <!--end product card-->

                    <!--end product card-->
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <span class="loading"
                            style="text-align: center;font-size: 17px;color: green;display:none">Products are
                            loading....</span>
                        <span class="not_found text-danger" style="text-align: center;font-size: 17px;display:none">No
                            Products Found</span>

                    </div>
                </div>

                <!--<div class="pagination-area mt-20 mb-20">-->
                <!--    <nav aria-label="Page navigation example">-->
                <!--        <ul class="pagination justify-content-start">-->
                <!--            <li class="page-item">-->
                <!--                <a class="page-link" href="#"><i class="fi-rs-arrow-small-left"></i></a>-->
                <!--            </li>-->
                <!--            <li class="page-item"><a class="page-link" href="#">1</a></li>-->
                <!--            <li class="page-item active"><a class="page-link" href="#">2</a></li>-->
                <!--            <li class="page-item"><a class="page-link" href="#">3</a></li>-->
                <!--            <li class="page-item"><a class="page-link dot" href="#">...</a></li>-->
                <!--            <li class="page-item"><a class="page-link" href="#">6</a></li>-->
                <!--            <li class="page-item">-->
                <!--                <a class="page-link" href="#"><i class="fi-rs-arrow-small-right"></i></a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </nav>-->
                <!--</div>-->
            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar mt-30 d-none d-lg-block">
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">{{$brandproducts->name}}</h5>
                    <ul>
                   
                        @if(isset($brandproducts) >0)
                           
                                <?php
                                    $img = $brandproducts->getImage($brandproducts->logo);
                                   ?>
                                
                                    <li>
                                        <a href="javascript:;" class="select_cat" id="{{ $brandproducts->id }}"> <img
                                                src="{{ asset('uploads/files/'.$img) }}"
                                                alt="" />{{ $brandproducts->name }}</a>
                                                
                                    </li>
                              
                       
                        @endif
                    </ul>
                </div>
                <!-- Fillter By Price -->
                <!--<div class="sidebar-widget price_range range mb-30">-->
                <!--    <h5 class="section-title style-1 mb-30">Fill by price</h5>-->
                <!--    <div class="price-filter">-->
                <!--        <div class="price-filter-inner">-->
                <!--            <div id="slider-range" class="mb-20"></div>-->
                <!--            <div class="d-flex justify-content-between">-->
                <!--                <div class="caption">From: <strong id="slider-range-value1" class="text-brand"></strong>-->
                <!--                </div>-->
                <!--                <div class="caption">To: <strong id="slider-range-value2" class="text-brand"></strong>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <div class="list-group">-->
                <!--        <div class="list-group-item mb-10 mt-10">-->
                <!--            <label class="fw-900">Color</label>-->
                <!--            <div class="custome-checkbox">-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox1"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox1"><span>Red (56)</span></label>-->
                <!--                <br />-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox2"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox2"><span>Green (78)</span></label>-->
                <!--                <br />-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox3"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox3"><span>Blue (54)</span></label>-->
                <!--            </div>-->
                <!--            <label class="fw-900 mt-15">Item Condition</label>-->
                <!--            <div class="custome-checkbox">-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox11"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox11"><span>New (1506)</span></label>-->
                <!--                <br />-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox21"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox21"><span>Refurbished-->
                <!--                        (27)</span></label>-->
                <!--                <br />-->
                <!--                <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox31"-->
                <!--                    value="" />-->
                <!--                <label class="form-check-label" for="exampleCheckbox31"><span>Used (45)</span></label>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--    <a href="shop-grid-right.html" class="btn btn-sm btn-default"><i class="fi-rs-filter mr-5"></i>-->
                <!--        Fillter</a>-->
                <!--</div>-->
                <!-- Product sidebar Widget -->
                <div class="sidebar-widget product-sidebar mb-30 p-30 bg-grey border-radius-10">
                    <h5 class="section-title style-1 mb-30">New products</h5>
                    @if(isset($newproducts) && sizeof($newproducts)>0)


                        @foreach($newproducts as $newpro)
                            <?php
                                    $img = $newpro->getImage($newpro->thumbnail);
                                    ?>
                            <div class="single-post clearfix">
                                <div class="image">
                                    <img src="{{ asset('uploads/files/'.$img) }}" alt="#" />
                                </div>
                                <div class="content pt-10">
                                    <h5><a href="/product/detail/{{ $newpro->slug }}">{{ $newpro->name }}</a></h5>
                                    <p class="price mb-0 mt-5">AED {{ round($newpro->price,2) }}</p>
                                    <!--<div class="product-rate">-->
                                    <!--    <div class="product-rating" style="width: 90%"></div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!--<div class="banner-img wow fadeIn mb-lg-0 animated d-lg-block d-none">-->
                <!--    <img src="assets/imgs/banner/banner-11.png" alt="" />-->
                <!--    <div class="banner-text">-->
                <!--        <span>Oganic</span>-->
                <!--        <h4>-->
                <!--            Save 17% <br />-->
                <!--            on <span class="text-brand">Oganic</span><br />-->
                <!--            Juice-->
                <!--        </h4>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
        @if(isset($storedeals) && sizeof($storedeals)>0)
        <section class="product-tabs section-padding position-relative px-3">
            @foreach($storedeals as $storedeal)
                <div class="container-fluid">
                    <div class="section-title">
                        <h3>{{ ucwords($storedeal->title) }}</h3>
                    </div>
                    <div class="row">
                        <?php $storedealsproducts = App\Models\StoredealProduct::where('storedeal_id', $storedeal->id)->get();?>
                        @foreach($storedealsproducts as $store_deal_product)
                            <?php $storeproducts = App\Models\Product::where('id', $store_deal_product->product_id)->get();?>
                            @foreach($storeproducts as $storproduct)
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <div class="product-cart-wrap style-2">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img">
                                                <a href="/product/detail/{{ $storproduct->slug }}">
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
                                                <p><a
                                                        href="/product/detail/{{ $storproduct->slug }}">{{ $storproduct->name }}</a>
                                                </p>
                                                <!--<div class="product-rate-cover">-->
                                                <!--    <div class="product-rate d-inline-block">-->
                                                <!--        <div class="product-rating" style="width: 90%"></div>-->
                                                <!--    </div>-->
                                                <!--    <span class="font-small ml-5 text-muted"> (4.0)</span>-->
                                                <!--</div>-->
                                                <!-- <div>
                                                <span class="font-small text-muted">By <a
                                                        >{{ $storproduct->store->name }}</a></span>
                                            </div> -->

                                                <div class="product-card-bottom">
                                                    <div class="product-price">
                                                        <?php $stordeladiscount = $storproduct->price * $storedeal->discount / 100;
                                                            $dealvalues = $storproduct->price - $stordeladiscount
                                                            ?>
                                                        <span>AED {{ round($dealvalues) }}</span>
                                                        <span class="old-price">{{ round($storproduct->price) }}</span>
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
</main>
@endsection
@section('scripts')

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype"
    async>
</script>
<script>
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
                    window.location.href = "/login";
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
<script>
    products()

    function products() {
        var category_id = "{{ $category_id }}";
        console.log(category_id)
        $.ajax({
            url: "/api/products/brand",
            type: "get",
            data: {
                category_id: category_id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    $(".append_here_products").html(response['html'])
                    $(".product_count").html(response['product_count'])

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }


    // $(document).on('click', '.select_cat', function () {
    //     $(".append_here_products").html('')

    //     products()
    // });
    $(document).on('click', '.select_cat', function () {
        $(".append_here_products").html('')
        var category_id = $(this).attr('id');
        $.ajax({
            url: "/api/products/brand",
            type: "get",
            data: {
                category_id: category_id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    $(".append_here_products").html(response['html'])
                    $(".product_count").html(response['product_count'])

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    $(document).on('click', '.add-wishlist', function () {
        var id = $(this).attr('id');
        $.ajax({
            url: "/add/wishlist",
            type: "get",
            data: {
                id: id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                    window.location.href = "/login";
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $(".wishlist_count").html(response['wishlist_count'])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

</script>
@endsection
