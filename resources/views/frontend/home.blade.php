@extends('frontend/layout/master')
@section('title')
Kwikcaart | Home
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

   @include('frontend/deals')

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
    $(document).on('click', '.add_cart', function () {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $(this).parent().parent().find(".qty_value").val();
        $(this).parent().parent().find(".qty_value").val(1);
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
