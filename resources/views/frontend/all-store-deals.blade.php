@extends('frontend/layout/master')
@section('title')
Safeer | Home
@endsection
@section('frontend/content')

<?php
$today = \Carbon\Carbon::today();
$storedeals = App\Models\StorewiseDeal::where('store_id', Session::get('store_id'))->where('status', 0)->where('featured', 0)->where('start_date', '<=', $today)->where('end_date', '>', date('Y-m-d'))->get();
?>

@if(isset($storedeals) && sizeof($storedeals)>0)
    <section class="product-tabs section-padding position-relative px-3">
        @foreach($storedeals as $storedeal)
        <div class="container">
            <div class="section-title">
                <h3>{{ ucwords($storedeal->title) }}</h3>
                <a href="{{url('store/deals/list')}}" class="btn">View More</a>
            </div>
            <div class="row row-cols-3 row-cols-sm-3 row-cols-md-6 row-cols-lg-6 ">
                <?php $storedealsproducts = App\Models\StoredealProduct::where('storedeal_id', $storedeal->id)->take('12')->get(); ?>
                @foreach($storedealsproducts as $store_deal_product)
                <?php $storeproducts = App\Models\Product::where('id', $store_deal_product->product_id)->get(); ?>
                @foreach($storeproducts as $storproduct)
                <div class="col p-0">
                    <div class="product-cart-wrap style-2">
                        <div class="product-img-action-wrap">
                            <div class="product-img">
                                <a href="/product/detail/{{ $storproduct->slug }}">
                                    <img src="{{ asset('uploads/files/'.$storproduct->getImage($storproduct->thumbnail)) }}" alt="" />
                                </a>
                            </div>
                        </div>
                        <div class="product-content-wrap">
                            <div class="deals-countdown-wrap">
                                <div class="deals-countdown" data-countdown="{{ $storedeal->end_date }}">
                                </div>
                            </div>
                            <div class="">
                                <!--<div class="product-category">-->
                                <!--    <a-->
                                <!--        href="{{ route('shop',$storproduct->id) }}">{{ $storproduct->category->name }}</a>-->
                                <!--</div>-->
                                <h6><a href="/product/detail/{{ $storproduct->slug }}">{{ $storproduct->name }}</a>
                                </h6>

                                <div class="product-rate-cover">
                                    <!--<div class="product-rate d-inline-block">-->
                                    <!--    <div class="product-rating" style="width: 90%"></div>-->
                                    <!--</div>-->
                                    <!--<span class="font-small ml-5 text-muted"> (4.0)</span>-->
                                </div>

                                <div class="product-price">
                                    <?php
                                    if ($store_deal_product->discount_type == 'percentage') {
                                        $stordeladiscount = $storproduct->price * $store_deal_product->discount / 100;
                                        $dealvalues = $storproduct->price - $stordeladiscount;
                                    } elseif ($store_deal_product->discount_type == 'flat') {
                                        $dealvalues = $storproduct->price - $store_deal_product->discount;
                                    }

                                    ?>
                                    <span>AED {{ round($dealvalues,2) }}</span>
                                    <span class="old-price">{{ round($storproduct->price,2) }}</span>
                                </div>
                                <div class="product-card-bottom">

                                    <div class="detail-qty border radius">
                                        <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                        <input type="text" name="quantity" class="qty-val qty_value" id="qty_value" value="1" min="1">
                                        <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                    </div>
                                    <div class="add-cart">
                                        <a class="add add_cart" href="javascript:;" id="{{ $storproduct->id }}"><i class="fi-rs-shopping-cart mr-5"></i>
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

@endsection
@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype" async>
</script>
<script>
    $(document).on('click', '.add_cart', function() {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $(".qty_value").val();
        $.ajax({
            url: "/add/cart/" + id,
            type: "get",
            data: {
                qty: qty
            },
            dataType: "JSON",
            cache: false,
            success: function(response) {
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
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
@endsection