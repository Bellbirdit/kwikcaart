   @extends('frontend/layout/master')
   @section('title')
   Safeer | Deals
   @endsection
   @section('frontend/content')

   <?php
    $today = \Carbon\Carbon::today();
    $deals = App\Models\Deals::where('status', 0)->where('start_date', '<=', $today)->where('end_date', '>', date('Y-m-d'))->get();
    ?>

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
                                        <span>AED {{ round($dealvalue) }}</span>
                                        <span class="old-price">{{ round($product->price) }}</span>
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
   @endsection

   @section('scripts')


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