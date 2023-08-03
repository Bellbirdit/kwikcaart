@extends('frontend/layout/master')
@section('title')
Safeer | Shop
@endsection
@section('frontend/content')

<main class="main">

    <div class="container-fluid mb-30">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                   
                </div>
                <div class="row product-grid append_here_products">
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

            </div> 
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar mt-5">
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">Fresh Dairy</h5>
                    <ul>
                        @if(isset($categories) && sizeof($categories)>0)
                            @foreach($categories as $mcat)
                                <?php
                                    $img = $mcat->getImage($mcat->icon);
                                    $proshopcount = App\Models\Product::where('category_id',$mcat->id)->where('store_id','like', '%' . session::get('store_id'). '%')->count() ?>
                               
                                    <li>
                                        <a href="javascript:;" class="select_cat" id="{{ $mcat->id }}"> <img
                                                src="{{ asset('uploads/files/'.$img) }}"
                                                alt="" />{{ $mcat->name }}</a>
                                                <!--<span class="count">{{ $proshopcount }}</span>-->
                                    </li>
                               
                            @endforeach
                        @endif
                    </ul>
                </div>
               
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
                                    <div class="product-rate">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
             
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
            url: "/api/products/category",
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
            url: "/api/products/category",
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
