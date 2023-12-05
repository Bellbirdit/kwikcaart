@extends('frontend/layout/master')
@section('title')
Kwikcaart | {{$catpro->name}}
@endsection
@section('frontend/content')
<style>
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

<main class="main">
   
    </div>
    <div class="container-fluid mb-30 p-2">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <div class="page-header breadcrumb-wrap py-2 my-2">
                    <div class="container">
                        <div class="breadcrumb">
                            <a href="{{ url('/') }}" rel="nofollow"><i
                                    class="fi-rs-home mr-5"></i>Home</a>
                            <span></span> <a href="javascript:;">{{ $catpro->name }}</a> <span></span>

                        </div>
                    </div>
                </div>
                <div class="row product-grid append_here_products">
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
           
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">{{$catpro->name}}</h5>
                    <ul>
                        @php
                            $catprosub = App\Models\Category::where('parent_id',$catpro->id)->get()
                        @endphp
                        @if(isset($catprosub) && sizeof($catprosub)>0)
                            @foreach($catprosub as $mcat)
                                @php
                                    $catprosubsub = App\Models\Category::where('parent_id',$mcat->id)->get();
                                @endphp
                                @foreach($catprosubsub as $catprosubsu)
                                    @php
                                        $img = $catprosubsu->getImage($catprosubsu->icon);
                                        $proshopcount =
                                        App\Models\Product::where('category_id',$catprosubsu->id)->where('store_id','like',
                                        '%' . session::get('store_id'). '%')->count()
                                    @endphp
                                    @if($proshopcount > '0')
                                    <li>
                                        <a href="javascript:;" class="select_cat" id="{{ $catprosubsu->id }}"> 
                                        <!--<img src="{{ asset('uploads/files/'.$img) }}"alt="" />-->
                                        {{ $catprosubsu->name }}</a>
                                         <!--<span class="count">{{ $proshopcount }}</span> -->
                                    </li>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </ul>
                </div>
                <!-- Product sidebar Widget -->
                <div class="sidebar-widget product-sidebar mb-30 p-30 bg-grey border-radius-10">
                    <h5 class="section-title style-1 mb-30">Latest Product</h5>
                    @php
                    $allproducts = App\Models\StoreProducts::where('store_id', Session::get('store_id'))->where('stock', 'yes')->latest()->take('3')->get();
                        @endphp
                        @foreach ($allproducts as $pro) 
                        @php
                            $products = App\Models\Product::where('id', $pro->product_id)->where('stock', 'yes')->where('published', 1)->get();
                            @endphp
                            @foreach ($products as $newpro)
                            <?php
                                $img = $newpro->getImage($newpro->thumbnail);
                                ?>
                            <div class="single-post clearfix">
                                <div class="image">
                                    <img src="{{ asset('uploads/files/'.$img) }}" alt="#" />
                                </div>
                                @php
                                $priceArray = $newpro->get_deal_price();
                                $price = $priceArray['price'];
                                @endphp
                                <div class="content pt-10">
                                    <h5><a href="/product/detail/{{ $newpro->slug }}">{{ $newpro->name }}</a></h5>
                                    <p class="price mb-0 mt-5">AED {{ $price }}</p>

                                </div>
                            </div>
                        @endforeach
                        @endforeach
                </div>
                  <!-- Fillter By brand -->
                
            </div>
        </div>
    </div>
    @include('frontend/deals')
    
</main>
@endsection
@section('scripts')

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype"
    async>
</script>
<script>
    $(document).on('click', '.qty-up', function(e){
      e.preventDefault();
      var id = $(this).attr('data-id');
      var input = $('#qty_val'+id);
      var qty = parseInt(input.val());
      qty++;
      input.val(qty);
    });

    $(document).on('click', '.qty-down', function(e){
      e.preventDefault();
      var id = $(this).attr('data-id');
      var input = $('#qty_val'+id);
      var qty = parseInt(input.val());
      if(qty > 1) {
        qty--;
        input.val(qty);


      }
    });

</script>

<script>
   $(document).on('click', '.add_cart', function () {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $("#qty_val"+id).val();
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
                    $("#total").html(response['total'])
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
            url: "/api/products/categorys",
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
<script>
     
$(document).on('click', '.select_brand', function () {
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
</script>
@endsection
