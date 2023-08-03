@extends('frontend/layout/master')
@section('title')
Safeer | Home
@endsection
@section('frontend/content')
<style>
    .catscroll {
        -ms-overflow-style: none;
        /* Internet Explorer 10+ */
        scrollbar-width: none;
        /*irefox */
    }

    .catscroll::-webkit-scrollbar {
        display: none;
        /* Safari and Chrome */
    }

</style>


<main class="main ">
    
    
@php 
    $store_id = Session::get('store_id');
@endphp

@if(isset($store_id))

@else
    <section class="home-slider position-relative mb-30 px-3">
        @php 
            $sliders = App\Models\Slider::all();
        @endphp
        <div class="container-fluid">
            <div class="home-slide-cover mt-3">
                <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                    @foreach($sliders as $slider)
                        <?php $img = $slider->getImage($slider->slider);?>
                        <div class="single-hero-slider single-animation-wrap">
                            <img src="{{ asset('/uploads/files/' . $img) }}"> 
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
   @endif
<!--category offers-->

  @php
       $catdeals = App\Models\CategorySale::where('store_id',Session::get('store_id'))->get();
   @endphp
    @if(isset($catdeals) && sizeof($catdeals)>0)
 <section class="px-3">
        <div class="container-fluid overflow-hidden">
            <div class="brands-section">
                <div class="title" style="margin-top: 30px; margin-left: 50px;">
                        <p style="font-size: 12px;font-weight: bold;">Trending Categories</p>
                        
                    </div>
                <div class="swiper2">
                  <div class="swiper-wrapper py-3">
                 
                   @foreach($catdeals as $cadeal)

                   @php
                    $category = App\Models\Category::where('id',$cadeal->category_id)->first();  
                   
                   @endphp
                   @if($category)
                    <div class="swiper-slide">
                        <div class="px-2">
                            <div class=" mx-2 wow animate__animated animate__fadeInUp feature-cat" data-wow-delay=".1s">
                                <figure class="f-category-img">
                                    <a href="{{ route('cat-products',$cadeal->category_id) }}">
                                        
                                         <img src="{{ asset('uploads/files/'.$category->getImage($category->icon)) }}"
                                                        alt="" />
                                    </a>
                                </figure>
                                <span class="f-category-spn">
                                    <a href="{{ route('cat-products',$cadeal->category_id) }}">{{$category->name}}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                  </div>
                </div>
            </div>
        </div>
    </section>

@endif

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
                                <h6><a href="/product/detail/{{ $storproduct->slug }}">{{ $storproduct->name }}</a>
                                </h6>

                                <div class="product-rate-cover">
         
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
    <!-- Storewise deal end -->


    <!-- all store deals -->
    @if(isset($deals) && sizeof($deals)>0)
    <section class="product-tabs section-padding position-relative px-3">
        @foreach($deals as $deal)
        <div class="container">
            <div class="section-title style-2 wow animate__animated animate__fadeIn">
                <h3>{{ ucwords($deal->title) }}</h3>
                <a href="{{url('all/deals/list')}}" class="btn">View More</a>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                    <div class="row product-grid-4">
                        <?php $deals_product = App\Models\DealProduct::where('deal_id', $deal->id)->take('12')->get(); ?>
                        @foreach($deals_product as $deal_product)
                        <?php $products = App\Models\Product::where('id', $deal_product->product_id)->get(); ?>
                        @foreach($products as $product)
                        <div class="col-lg-2 col-md-2 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">

                                        <a href="/product/detail/{{ $product->slug }}">
                                            <img class="default-img" src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}" alt="" />
                                            <img class="hover-img" src="{{ asset('uploads/files/'.$product->getImage($product->thumbnail)) }}" alt="" />
                                        </a>


                                    </div>
                                    @if($deal_product->discount_type == 'percentage')
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot">{{$deal_product->discount}}%</span>
                                    </div>
                                    @elseif($deal_product->discount_type == 'flat')
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot">-{{$deal_product->discount}}AED</span>
                                    </div>
                                    @endif

                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="/product/detail/{{ $product->slug }}">{{ $product->category->name }}</a>
                                    </div>
                                    <h6><a href="/product/detail/{{ $product->slug }}">{{ $product->name }}</a> </h6>
                                    <div class="text-center detail-info col-md-5" data-title="Stock">

                                    </div>
                                    <div class="product-price">
                                        <?php
                                        if ($deal_product->discount_type == 'percentage') {
                                            $deladiscount = $product->price * $deal_product->discount / 100;
                                            $dealvalue = $product->price - $deladiscount;
                                        } else if ($deal_product->discount_type == 'flat') {
                                            $dealvalue = $product->price - $deal_product->discount;
                                        }

                                        ?>
                                        <span>AED {{ round($dealvalue,2) }}</span>
                                        <span class="old-price">{{ round($product->price,2) }}</span>
                                    </div>
                                    <div class="product-card-bottom">
                                        <div class="detail-qty border radius">
                                            <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                            <input type="text" name="quantity" class="qty-val qty_value" id="qty_value" value="1" min="1">
                                            <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                        </div>
                                        <div class="add-cart">
                                            <a class="add add_cart" href="javascript:;" id="{{ $product->id }}"><i class="fi-rs-shopping-cart mr-5"></i>
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



  <section class="section-padding mb-30 px-3">

        <div class="container">
          
                <div class="row">
                @if(isset($subcategories) && sizeof($subcategories) >0)
                    @foreach($subcategories as $cat)
                   @php
                    $cat_img = $cat->getImage($cat->banner);
                    @endphp
                    <div class=" col-lg-6 col-md-6 mb-sm-5 mb-md-0 wow animate__animated animate__fadeInUp"
                        data-wow-delay="0">
                        <figure class="">
                            <a href="{{ route('cat-products',$cat->id) }}">
                                @if($cat_img!=null)
                                <img class="bn-shadow" src="{{ asset('uploads/files/'.$cat_img) }}" alt="" />
                                @else
                                <img class="bn-shadow" src="https://via.placeholder.com/1000x250" alt="" />
                                @endif
                            </a>
                        </figure>
                        <div class="swiper mySwiper container">
                            <div class="swiper-wrapper text-center py-3 ">
                               
                                @if(isset($cat->cbannercat) && sizeof($cat->cbannercat)>0)
                                    @foreach($cat->cbannercat as $scat)
                                        @php
                                         $img = $scat->getImage($scat->icon);
                                         @endphp
                                        <div class="swiper-slide sub-cat">
                                            <div>
                                                <!-- <a href="{{ route('shop_categroy',$scat->id) }}">  -->
                                                 @if($img!=null)
                                                <img src="{{ asset('uploads/files/'.$img) }}" alt="">
                                                @else
                                                <img class="bn-shadow" src="https://via.placeholder.com/250x250" alt="" />
                                                @endif
                                                </a>
                                            </div>
                                            @php
                                                $ssscat = App\Models\Category::where('parent_id',$scat->id)->get();
                                                $sscat = $ssscat->unique('title');
                                            @endphp
                                            @foreach($sscat as $sct)
                                            <span><a href="{{ route('cat-products',$sct->id) }}"> {{ $scat->name }}</a></span>
                                            @endforeach
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
    <section class="px-3">
        <div class="container overflow-hidden">
            <div class="brands-section">
                <!-- Slider main container -->
                <div class="swiper2">
                  <!-- Additional required wrapper -->
                  <div class="swiper-wrapper py-3">
                    <!-- Slides -->
                    @php
                    $brands = App\Models\Brand::all()
                    @endphp

                    @foreach($brands as $bra)
                    <div class="swiper-slide">
                        <div class="brand px-3">
                           <a href="/shop/brand/{{$bra->id}}"><img class="my-auto" src="{{ asset('uploads/files/'.$bra->getImage($bra->logo)) }}" alt="" /></a> 
                        </div>
                      
                    </div>
        @endforeach
                  </div>
                </div>
                
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
    $(document).on('click', '.add_cart', function () {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $(".qty_value").val();
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

</script>
<script>
    var swiper = new Swiper(".mySwiper", {

        freeMode: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 5000,
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 3,
                spaceBetween: 10
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 4,
                spaceBetween: 10
            },
            // when window width is >= 640px
            640: {
                slidesPerView: 6,
                spaceBetween: 15
            }
        }
    });

</script>
<script>
  
    const swiper2 = new Swiper('.swiper2', {
   // Default parameters
   slidesPerView: 1,
   spaceBetween: 5,
   autoplay: {
     delay: 1000,
   },
   // Responsive breakpoints
   breakpoints: {
     // when window width is >= 320px
     320: {
       slidesPerView: 3,
       spaceBetween: 5
     },
     // when window width is >= 480px
     480: {
       slidesPerView: 7,
       spaceBetween: 5
     },
     // when window width is >= 640px
     640: {
       slidesPerView: 10,
       spaceBetween: 5
     }
   }
 })
  </script>

@endsection
