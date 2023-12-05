@extends('frontend/layout/master')
@section('title')
Kwikcaart | Product Detail
@endsection
@section('frontend/content')
<style>
    <style>
.product-img-action-wrap {
  position: relative;
}

.product-action-1.top-left {
  position: absolute;
  top: 0;
  left: 0;
}

.product-action-1.top-right {
  position: absolute;
  top: 0;
  right: 0;
}

.add_cart i {
   color: red;
}


</style>
@if(isset($products) && !empty($products))
<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> <a href="javascript:;">{{App\Models\Category::where('id',$products->category_id)->pluck('name')->first()}}</a> <span></span>
                {{ $products->name }}
            </div>
        </div>
    </div>
    <?php
        $img = $products->getImage($products->thumbnail);
        $galleryimg1 = $products->getImage($products->galleryimg1);
        $galleryimg2 = $products->getImage($products->galleryimg2);
        $galleryimg3 = $products->getImage($products->galleryimg3);
        $galleryimg4 = $products->getImage($products->galleryimg4);
        ?>
      <?php

$count = App\Models\Review::where('product_id', $products->id)->where('status', 1)->count();

if ($count > 0) {

    //Five Star
    $five_star = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 5)->count();
    $total_five_star = $five_star / $count * 100;

    //Five Star
    $four_star = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 4)->count();

    $total_four_star = $four_star / $count * 100;

    //Five Star
    $three_star = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 3)->count();

    $total_three_star = $three_star / $count * 100;

    //Five Star
    $two_star = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 2)->count();

    $total_two_star = $two_star / $count * 100;

    //Five Star
    $one_star = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 1)->count();

    $total_one_star = $one_star / $count * 100;

    $star_sum = App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 5)->sum('rating') + App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 4)->sum('rating') + App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 3)->sum('rating') + App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 2)->sum('rating') + App\Models\Review::where('product_id', $products->id)->where('status', 1)->where('rating', 1)->sum('rating');

    $sum = $star_sum / $count;

    $percentage = ($sum / 5) * 100;
} else {
    $percentage = 0;
    $sum = 0;
    $total_five_star = 0;
    $total_four_star = 0;
    $total_three_star = 0;
    $total_two_star = 0;
    $total_one_star = 0;
}

?>
    <div class="container mb-30 pt-30">
        <div class="row">
            <div class="col-xl-11 col-lg-12 m-auto">
                <div class="row">
                    <div class="col-xl-9">
                        <div class="product-detail accordion-detail">
                            <div class="row mb-50 mt-30">
                                <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                    <div class="detail-gallery">
                                        <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                        <!-- MAIN SLIDES -->
                                        <div class="product-image-slider">
                                            <figure class="border-radius-10" style="width: 438px; !important">
                                                <img src="{{ asset('uploads/files/'.$img) }}" alt="product image" />
                                            </figure>
                                            @if($galleryimg1 != '')
                                           <figure class="border-radius-10" style="width: 438px; !important">
                                                <img src="{{ asset('uploads/files/'. $galleryimg1  ) }}" alt="product image" />
                                            </figure>
                                            @endif
                                            @if($galleryimg2 != '')
                                            <figure class="border-radius-10" style="width: 438px; !important">
                                                <img src="{{ asset('uploads/files/'. $galleryimg2  ) }}" alt="product image" />
                                            </figure>
                                            @endif
                                            @if($galleryimg3 != '')
                                            <figure class="border-radius-10" style="width: 438px; !important">
                                                <img src="{{ asset('uploads/files/'. $galleryimg3  ) }}" alt="product image" />
                                            </figure>
                                            @endif
                                             @if($galleryimg4 != '')
                                            <figure class="border-radius-10" style="width: 438px; !important">
                                                <img src="{{ asset('uploads/files/'. $galleryimg4  ) }}" alt="product image" />
                                            </figure>
                                            @endif
                                            <!-- <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-1.jpg" alt="product image" />
                                            </figure>
                                            <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-3.jpg" alt="product image" />
                                            </figure>
                                            <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-4.jpg" alt="product image" />
                                            </figure>
                                            <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-5.jpg" alt="product image" />
                                            </figure>
                                            <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-6.jpg" alt="product image" />
                                            </figure>
                                            <figure class="border-radius-10">
                                                <img src="assets/imgs/shop/product-16-7.jpg" alt="product image" />
                                            </figure> -->
                                        </div>
                                        <!-- THUMBNAILS -->
                                         <div class="slider-nav-thumbnails">
                                            <div>
                                                <img src="{{ asset('uploads/files/'.$img) }}" alt="product image" />

                                            </div>
                                            @if($galleryimg1 != '')
                                             <div>
                                                <img src="{{ asset('uploads/files/'.$galleryimg1) }}" alt="product image" />

                                            </div>
                                            @endif
                                            @if($galleryimg2 != '')
                                             <div>
                                                <img src="{{ asset('uploads/files/'.$galleryimg2) }}" alt="product image" />

                                            </div>
                                            @endif
                                            @if($galleryimg3 != '')

                                             <div>
                                                <img src="{{ asset('uploads/files/'.$galleryimg3) }}" alt="product image" />

                                            </div>
                                            @endif
                                             @if($galleryimg4 != '')

                                             <div>
                                                <img src="{{ asset('uploads/files/'.$galleryimg4) }}" alt="product image" />

                                            </div>
                                            @endif
                                            </div>
                                   
                                    </div>
                                   
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-info pr-30 pl-30">
                                        <h2 class="title-detail">{{ ucwords($products->name) }}</h2>
                                        <div class="product-detail-rating">
                                            <div class="product-rate-cover text-end">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width: {{$percentage}}%"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> ({{$count}} reviews)</span>
                                            </div>
                                        </div> 
                                        

                                <div class="clearfix product-price-cover">
                                    <div class="product-price primary-color float-left">
                                        @php
                                        $priceArray = $products->get_deal_price();
                                        $price = $priceArray['price'];
                                        @endphp
                                        @if(isset($products))
                                            <span class="current-price text-brand">AED {{round($price,2)}}</span>
                                            @if(isset($priceArray['old_price']))
                                            <span class="old-price font-md ml-15">{{$priceArray['old_price']}}</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                        <div class="row">
                                            <ul class="col-md-12">
                                                <li class="mb-0">Barcode: <a href="#">{{$products->barcode}}</a></li>
                                                <li class="mb-0">Brand: <span class="text-brand">{{App\Models\Brand::where('id',$products->brand_id)->pluck('name')->first()}}</span></li>
                                                <li class="mb-0">Category:<span class="text-brand"> {{App\Models\Category::where('id',$products->category_id)->pluck('name')->first()}}</span>
                                                </li>
                                                
                                            </ul>
                                        </div>
                                       
                                        <div class="short-desc mb-30">
                                            <h6 class="py-1 text-grey">About this item</h6>
                                            <p style="text-align:justify">{!! nl2br($products->short_description) !!}</p>
                                        </div>
                                        
                                    </div>
                                    <!-- Detail Info -->
                                </div>
                            </div>

                            @if(isset($products->product_acess) && !empty($products->product_acess))

                            <div class="product-info my-5">
                                <div class="row mb-2">
                                    <div class="col-md-7">
                                        <h4 class="mt-10 mb-10">Frequently Bought Together</h4>
                                    </div>
                                    <div class="col-md-5 ">
                                        <a href="javascript:;" class="button button-add-to-cart add_to_cart float-end">Add  To
                                            Cart</a>
                                    </div>
                                </div>
                                <div class="row related-products">
                                    @foreach(json_decode($products->product_acess) as $p)
                                      <?php $fre_products = App\Models\Product::where('id', $p)->get();?>
                                        @foreach($fre_products as $pro)
                                        <div class="col-lg-3 col-md-3 col-12 col-sm-6">
                                            <div class="product-cart-wrap hover-up">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="/product/detail/{{ $pro->slug }}" tabindex="0">
                                                            <img class="default-img" src="{{ asset('uploads/files/'.$pro->getImage($pro->thumbnail)) }}"
                                                                alt="" />
                                                            <img class="hover-img" src="{{ asset('uploads/files/'.$pro->getImage($pro->galleryimg2)) }}"
                                                                alt="" />
                                                        </a>
                                                    </div>
                                                </div>
                                            
                                                <div class="product-content-wrap">
                                                    <h2><a href="/product/detail/{{ $pro->slug }}" tabindex="0">{{$pro->name}}</a></h2>
                                                    <div class="product-price">
                                                        <!--<span>AED {{$pro->discount_price($pro)}} </span>-->
                                                        <!--<span class="old-price">AED {{$pro->price}}</span>-->
                                                     @php
                                        $priceArray = $pro->get_deal_price();
                                        $price = $priceArray['price'];
                                        @endphp
                                                    <span class="current-price text-brand">AED {{ $price }}</span>
                                               
                                                        <input type="checkbox" name="checkbox[]" class="checkbox"  id="checkbox{{$pro->id}}" value="{{$pro->id}}" style="width: 20px;height: 20px;">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <div class="product-info">
                                <div class="tab-style3">
                                    <ul class="nav nav-tabs text-uppercase">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Description-tab" data-bs-toggle="tab"
                                                href="#Description">Description</a>
                                        </li>
                                        <li class="nav-item">
                                            <?php $review = 0;
                                                
                                                if ($review > 0) {
                                                    $review = App\Models\Review::where('product_id', $products->id)->where('status', 1)->count();
                                                }
                                                ?>
                                            <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab"
                                                href="#Reviews">Reviews <span>({{$review }})</span></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content shop_info_tab entry-main-content">
                                        <div class="tab-pane fade show active" id="Description">
                                            <div class="">
                                               
                                                {!! ($products->description) !!}
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="Reviews">
                                            <!--Comments-->
                                            <div class="comments-area">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <h4 class="mb-30">Customer questions & answers</h4>
                                                        <div class="comment-list" style="overflow: scroll;max-height: 500px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <h4 class="mb-30">Customer reviews</h4>
                                                        <div class="d-flex mb-30">
                                                            <div class="product-rate d-inline-block mr-15">
                                                                <div class="product-rating" style="width: {{$percentage}}%"></div>
                                                            </div>
                                                            <h6>{{round($sum,1)}} out of 5</h6>
                                                        </div>
                                                        <div class="progress">
                                                            <span>5 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{round($total_five_star)}}%" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100">{{round($total_five_star)}}%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>4 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{round($total_four_star)}}%" aria-valuenow="25" aria-valuemin="0"
                                                                aria-valuemax="100">{{round($total_four_star)}}%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>3 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{round($total_three_star)}}%" aria-valuenow="{{round($total_three_star)}}" aria-valuemin="0"
                                                                aria-valuemax="100">{{round($total_three_star)}}%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>2 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{round($total_two_star)}}%" aria-valuenow="{{round($total_two_star)}}" aria-valuemin="0"
                                                                aria-valuemax="100">{{round($total_two_star)}}%</div>
                                                        </div>
                                                        <div class="progress mb-30">
                                                            <span>1 star</span>
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{round($total_one_star)}}%" aria-valuenow="{{round($total_one_star)}}" aria-valuemin="0"
                                                                aria-valuemax="100">{{round($total_one_star)}}%</div>
                                                        </div>
                                                        <a href="#" class="font-xs text-muted">How are ratings
                                                            calculated?</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <style>
                                                .rate {
                                                            float: left;
                                                            height: 46px;
                                                            padding: 0 10px;
                                                        }
                                                        .rate:not(:checked) > input {
                                                            position:absolute;
                                                            top:-9999px;
                                                        }
                                                        .rate:not(:checked) > label {
                                                            float:right;
                                                            width:1em;
                                                            overflow:hidden;
                                                            white-space:nowrap;
                                                            cursor:pointer;
                                                            font-size:30px;
                                                            color:#ccc;
                                                        }
                                                        .rate:not(:checked) > label:before {
                                                            content: '★ ';
                                                        }
                                                        .rate > input:checked ~ label {
                                                            color: #ffc700;
                                                        }
                                                        .rate:not(:checked) > label:hover,
                                                        .rate:not(:checked) > label:hover ~ label {
                                                            color: #deb217;
                                                        }
                                                        .rate > input:checked + label:hover,
                                                        .rate > input:checked + label:hover ~ label,
                                                        .rate > input:checked ~ label:hover,
                                                        .rate > input:checked ~ label:hover ~ label,
                                                        .rate > label:hover ~ input:checked ~ label {
                                                            color: #c59b08;
                                                        }

                                            </style>
                                            <!--comment form-->
                                            <div class="comment-form">
                                                <h4 class="mb-15">Add a review</h4>

                                                <div class="row">
                                                    <div class="col-lg-8 col-md-12">
                                                        <form class="form-contact comment_form"
                                                            id="commentForm">
                                                            <input type="hidden" name="product_id" value="{{$products->id}}">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control w-100"
                                                                            name="review" required id="comment" cols="30"
                                                                            rows="9"
                                                                            placeholder="Write your Review about {{$products->name}}"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <input class="form-control" name="name"
                                                                            id="name" required type="text" placeholder="Name" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <input class="form-control" name="email"
                                                                            id="email" required type="email"
                                                                            placeholder="Email" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                             <div class="col-lg-12">
                                                                <div class="form-group">
                                                                    <div class="rate">
                                                                        <input type="radio" id="star5" name="rating" value="5" />
                                                                        <label for="star5" title="text">5 stars</label>
                                                                        <input type="radio" id="star4" name="rating" value="4" />
                                                                        <label for="star4" title="text">4 stars</label>
                                                                        <input type="radio" id="star3" name="rating" value="3" />
                                                                        <label for="star3" title="text">3 stars</label>
                                                                        <input type="radio" id="star2" name="rating" value="2" />
                                                                        <label for="star2" title="text">2 stars</label>
                                                                        <input type="radio" id="star1" name="rating" value="1" />
                                                                        <label for="star1" title="text">1 star</label>
                                                                    </div>
                                                                </div>
                                                             </div>
                                                             <br>
                                                             <div class="col-lg-12 text-center">
                                                                <div class="form-group">
                                                                    <button type="submit"
                                                                        class="button button-contactForm" id="btnSubmit"> <i class=" fa fa-spinner fa-spin fa-pulse" style="display:none"></i>  Submit
                                                                        Review</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-60">
                                <div class="col-12">
                                    <h2 class="section-title style-1 mb-30">Related products</h2>
                                </div>
                                <div class="col-12">
                                    <div class="row related-products">
                                        <?php $related_products = App\Models\Product::where('category_id', $products->category_id)->where('id', '!=', $products->id)->paginate(6);?>
                                        @foreach($related_products as $related)
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6 py-3">
                                            <div class="product-cart-wrap hover-up ">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-action-1 top-left">
                                                        <a aria-label="Add To Wishlist" class="action-btn add-wishlist" href="javascript:;" id="{{ $related->id }}"><i class="fi-rs-heart"></i></a>
                                                    </div>
                                                    <div class="product-action-1 top-right add-cart">
                                                        <a aria-label="Add To Cart" class="action-btn add add_cart" href="javascript:;" id="{{ $related->id }}"><i class="fi-rs-shopping-cart" style="color:red;"></i></a>
                                                    </div>
                                                    <div class="product-img product-img-zoom">
                                                        <a href="/product/detail/{{ $related->slug }}" tabindex="0">
                                                            <img class="default-img" src="{{ asset('uploads/files/'.$related->getImage($related->thumbnail)) }}" alt="" />
                                                            <img class="hover-img" src="{{ asset('uploads/files/'.$related->getImage($related->galleryimg2)) }}" alt="" />
                                                        </a>
                                                    </div>
                                                    <!--<div class="product-badges product-badges-position product-badges-mrg">-->
                                                    <!--    <span class="hot">Hot</span>-->
                                                    <!--</div>-->
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="/product/detail/{{ $related->slug }}" tabindex="0">{{ucwords($related->name)}}</a></h2>
                                                    <!--<div>-->
                                                    <!--( {{App\Models\Review::where('product_id',$related->id)->where('status',1)->count();}} reviews)-->
                                                    <!--</div>-->
                                                    <div class="product-price">
                                                @if(isset($related))
                                                     @php
                                        $priceArray = $related->get_deal_price();
                                        $price = $priceArray['price'];
                                        @endphp
                                                   
                                                    <span class="current-price text-brand">AED {{ $price }}</span>
                                                @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 primary-sidebar sticky-sidebar mt-30">
                        <div class="sidebar-widget widget-vendor mb-30  box-shadow-none">
                            <div class="detail-extralink " style="text-align:center">
                                <h4 class="text-center">
                                    <div class="product-price primary-color float-left" style="font-size: 20px;font-weight: 600;">
                                        @php
                                        $priceArray = $products->get_deal_price();
                                        $price = $priceArray['price'];
                                        @endphp
                                        <span class="current-price text-brand ">AED {{round($price,2)}}</span>
                                </div>
                                </h4>
                            
                                <div class="detail-qty border radius">
                                    <a href="#" class="qty-down"><i class="fi-rs-minus"></i></a>
                                    <input type="text" name="quantity" class="qty-val" id="qty_value" value="1" min="1">
                                    <a href="#" class="qty-up"><i class="fi-rs-plus"></i></a>
                                </div>
                                <div class="product-extra-link2">
                                    <!--<a  aria-label="Add To Cart" class="action-btn hover-up add_cart" href="javascript:;"  id="{{ $products->id }}"> <img id="{{ $products->id }}" src="{{ asset('frontend/assets/imgs/theme/icons/dn-cart.png') }}"alt="" style=" width: 25px;" /></a>-->
                                    <!--<a aria-label="Add To" class="action-btn hover-up   add-wishlist" href="javascript:;"  id="{{ $products->id }}"><img  src="{{ asset('frontend/assets/imgs/theme/icons/dn-heart.png') }}"alt="" style=" width: 25px;" /></a>-->
                                    <!--<a aria-label="Buy Now" class="action-btn hover-up btnBuyNowbtn" href="javascript:;"  id="{{ $products->id }}"><img  src="{{ asset('frontend/assets/imgs/theme/icons/dn-buy.png') }}"alt="" style=" width: 25px;" /></a>-->
                                    <div class="a_cart" style="width:140px;">
                                    <a  aria-label="Add To Cart" class="action-btn hover-up add_cart" href="javascript:;"  id="{{ $products->id }}"> Add To Cart</a>
                                     <!--<a aria-label="Buy Now" class="action-btn hover-up btnBuyNowbtn" style="display:none;" href="javascript:;"  id="{{ $products->id }}">Buy Now</a>-->
                                   
                                   </div>
                                   
                                     <a aria-label="Add To" class="action-btn hover-up   add-wishlist" href="javascript:;"  id="{{ $products->id }}"><img  src="{{ asset('frontend/assets/imgs/theme/icons/dn-heart.png') }}"alt="" style=" width: 25px;" /></a>
                                    
                                   </div>
                                
                            </div>

                        </div>
                        @if(Auth::check())
                        <div class="sidebar-widget widget-delivery mb-30  box-shadow-none">
                            <h5 class="section-title style-3 mb-20">Delivery</h5>
                            <ul>
                                <li>
                                    <i class="fi fi-rs-marker mr-10 text-brand"></i>
                                    @php
                                        $defaultaddress = App\Models\UserAddress::where('user_id',auth()->user()->id)->where('is_default',1)->first();
                                    @endphp
                                     @if(isset($defaultaddress))
                                     
                                     <span>{{ $defaultaddress->address }}</span>
                                     @else
                                    <span>
                                       {{auth()->user()->address}}
                                    </span>
                                    @endif
                                    <!-- <a href="#" class="change float-end">Change</a> -->
                                </li>
                            </ul>

                        </div>
                        @endif
                        <div class="sidebar-widget widget-vendor mb-30  box-shadow-none">
                            <h5 class="section-title style-3 mb-20">Delivery Options Available</h5>
                            @if($products->express_delivery == 'yes')
                            <div class="vendor-logo d-flex mb-30">
                                <div class="vendor-name ml-15">
                                    <h6>
                                        <a href="javascript:;">Express Delivery</a>
                                    </h6>
                                    <!--<div class="product-rate-cover ">-->
                                    <!--    <span class="font-small ml-5 text-muted">Get it within 60 Mins</span><br>-->
                                    <!--    <span class="font-small ml-5 text-muted">Extra charges AED 10</span>-->
                                    <!--</div>-->
                                </div>
                            </div>
                           
                          @elseif($products->express_delivery == 'no')
                                <div class="vendor-logo d-flex mb-30">
                                    <div class="vendor-name">
                                        <h6>
                                            <b style="color: red;" href="javascript:;">Home Delivery</b>
                                        </h6>
                                        
                                        @if(isset($standarddate->date) )
                                            <div class="product-rate-cover" style="font-size: 12px;">
                                                
                                                
                                                @if(isset($standarddate->date))
                                                    <span class="font-small text-muted">Date: {{ \Carbon\Carbon::parse($standarddate->date)->isoFormat('MMM Do YYYY')}}</span><br>
                                                @endif
                                                
                                                @if(isset($standardtime->start_time))
                                                    <span class="font-small text-muted">Next Slot: {{$standardtime->start_time}} To {{$standardtime->end_time}}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            
                            <div class="vendor-logo d-flex mb-30">
                                <div class="vendor-name">
                                    <h6>
                                        <b style="color: red;" href="javascript:;">Collect From Store</b>
                                    </h6>
                                    
                                    <div class="product-rate-cover" style="font-size: 12px;">

                                        
                                        @if(isset($pickuptimes->date))
                                        <span class="font-small text-muted">Date: {{ \Carbon\Carbon::parse($pickuptimes->date)->isoFormat('MMM Do YYYY')}}</span><br>
                                        @endif
                                        @if(isset($pickuptime->start_time))
                                        <span class="font-small text-muted">Next Slot: {{$pickuptime->start_time}} To {{$pickuptime->end_time}}</span>
                                        @endif
                                    </div>
                                   
                                     
                                </div>
                            </div>
                            <ul class="contact-infor">
                                <li><img src="{{asset('assets/imgs/theme/icons/icon-location.svg')}}" alt="" /><strong>{{$storeaddress->name}}
                                    </strong> <span>{{$storeaddress->address}}</span></li>

                            </ul>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
@endif

@endsection
@section('scripts')
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype"
    async>
</script>
<script>

    reviews()
    function reviews()
    {

        $(".comment-list").html('')
        var product_id = "{{$products->id}}";
        $.ajax({
            url: "/reviews/list/",
            type: "get",
            data:{
                product_id:product_id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    $(".comment-list").html(response['html'])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    }

        $("#commentForm").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/review/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        reviews()
                        $("#commentForm")[0].reset();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));

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


    $(document).on('click', '.btnBuyNowbtn', function(e) {
    var id = $(this).attr('id');

    e.preventDefault()
    $.ajax({
        url: '/add/cart/' + id,
        type: "get",
        dataType: "JSON",
        cache: false,
        beforeSend: function() {
            $("#shoppingCart" + id).css('display', 'none');
            $("#cartSpin" + id).css('display', 'inline-block');
        },
        complete: function() {
            $("#shoppingCart" + id).css('display', 'inline-block');
            $("#cartSpin" + id).css('display', 'none');

        },
        success: function(response) {
            console.log(response);
            if (response["status"] == "fail") {

                toastr.error('Fail', response['msg'])
                 window.location.href = "/login";

            } else if (response["status"] == "success") {
    
                window.location.href = "/cart";
            }
        },
        error: function(error) {
            console.log(error);
        }
    });

})

    $(document).on('click', '.add_cart', function () {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $("#qty_value").val();
        $.ajax({
            url: "/add/cart/" + id,
            type: "get",
            data:{qty:qty},
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

    $(document).on('click', '.add_to_cart', function () {
        $(".append_cart").html('')


        var products  = [];

         if($('.checkbox').is(':checked'))
         {



        $('.checkbox').each(function(){
            if($(this).is(':checked')){
                products.push($(this).val());
            }
        });


        $.ajax({
            url: "/add/multiple/cart",
            type: "get",
            data: {products: JSON.stringify(products)},
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
    }else{
            toastr.error('Select Product')
         }
    });



</script>

@endsection