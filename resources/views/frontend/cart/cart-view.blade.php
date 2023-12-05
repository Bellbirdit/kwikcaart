@extends('frontend/layout/master')
@section('title')
Kwikcaart | Cart
@endsection
@section('frontend/content')

<style>.quantity {
  display: flex;
  align-items: center;
  justify-content: start;
  padding: 0;
}
.quantity__minus,
.quantity__plus {
  display: block;
  width: 22px;
  height: 23px;
  margin: 0;
  background: #dee0ee;
  text-decoration: none;
  text-align: center;
  line-height: 23px;
}
.quantity__minus:hover,
.quantity__plus:hover {
  background: #575b71;
  color: #fff;
} 
.quantity__minus {
  border-radius: 3px 0 0 3px;
}
.quantity__plus {
  border-radius: 0 3px 3px 0;
}
.quantity__input {
  width: 32px;
  height: 19px;
  margin: 0;
  padding: 0;
  text-align: center;
  border-top: 2px solid #dee0ee;
  border-bottom: 2px solid #dee0ee;
  border-left: 1px solid #dee0ee;
  border-right: 2px solid #dee0ee;
  background: #fff;
  color: #8184a1;
}
.quantity__minus:link,
.quantity__plus:link {
  color: #8184a1;
} 
.quantity__minus:visited,
.quantity__plus:visited {
  color: #fff;
}
</style>
<main class="main">
        <section>
            <div class="container">
                <div class="page-header breadcrumb-wrap">
                    <div class="container-fluid">
                        <div class="breadcrumb">
                            <a href="{{url('/')}}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                            <span></span> Shop
                            <span></span> Cart
                        </div>
                    </div>
                </div>
                <div class="container-fluid mb-80 mt-50">
                    <div class="row"> 
                        <div class="col-lg-8 mb-40">
                            <h4 class="heading-2 mb-10">Your order</h4>
                            <div class="d-flex justify-content-between">
                            @php 
                                $count=app\Models\Cart::where('user_id',auth()->user()->id)->where('store_id', Session::get('store_id'))->where('status','pending')->count(); 
                            @endphp
                                <h6 class="text-body">There are <span class="">{{$count}}</span> products in your cart</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-8">
                            <div class=" border p-md-4 cart-totals ml-30">
                            <div class="row" style="font-weight: bold;">
                                <div class="col-md-6">
                                    <h2>Products</h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-3" >
                                        <div class="col-md-5">Unit price</div>
                                         <div class="col-md-2">Quantity</div>
                                          <div class="col-md-2">Subtotal</div>
                                           <div class="col-md-2">Remove</div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="">
                                    @php 
                                    $total = 0;
                                    @endphp
                                    <div class="row">
                                        @if(sizeof($carts) > 0)
                                        @foreach($carts as $cart)
                                         <div class="col-md-12">
                                             <div class="row ">
                                                <div class="image product-thumbnail col-md-1"><img src="{{asset('uploads/files/'.$cart->image)}}" alt="#" ></div>
                                                <div class="product-des product-name col-md-5">
                                                    <p class="mb-5"><a class="pname" href="javascript:;">{{$cart->name}}</a></p>
                                                    <!-- <div class="product-rate-cover">
                                                        <div class="product-rate d-inline-block">
                                                            <div class="product-rating" style="width:90%">
                                                            </div>
                                                        </div>
                                                        <span class="font-small ml-5 text-muted"> (4.0)</span>
                                                    </div> -->
                                            </div>
                                                <?php $vat =  App\Models\Product::where('id',$cart->product_id)->first();
                                                    $priceArray = $vat->get_deal_price();
                                                    $price = $priceArray['price'];
                                                     if($vat->vat_status == 'yes' )
                                                     {
                                                        $vat_price = DB::table('web_settings')->pluck('vat')->first();
                                                        $vat_price = $vat_price.'%';
                                                     }else if($vat->vat_status == 'no'){
                                                        $vat_price = 'No';
                                                     }else if($vat->vat_status == 'exemted'){
                                                        $vat_price = 'Exemted';
                                                     }else{
                                                        $vat_price = 0;
                                                     }
                                                ?>
                                              <div class="col-md-6">
                                                <div class="row">
                                                    <div class="price col-md-5 col-5" data-title="Price">
                                                    <p class="">AED {{$price}} </p>
                                                    </div>
                                                
                                                    <div class="text-center detail-info col-md-7 col-7" data-title="Stock">
                                                            <div class="row">
                                                                <div class="col-3 quantity ">
                                                                <a href="{{ route('cart.dcr',$cart->id) }}" class="quantity__minus"><span class="text-dark">-</span></a>
                                                                <input name="quantity" type="text" style="border:none" value="{{ $cart->quantity }}" class="quantity__input">
                                                                <a  href="{{ route('cart.incr',$cart->id) }}" class="quantity__plus"><span class="text-dark">+</span></a>
                                                                </div>
                                                                <div class="col-4">
                                                                 @php
                                                    $total += $cart->quantity*$price;
                                                    @endphp
                                                    <span style="margin: 0 15px;font-weight: bold;width:40px; ">  {{round($cart->quantity*$price,2)}} </span>
                                                                </div>
                                                                <div class="col-3">
                                                                <a style="margin-left:5px" id="{{ $cart->id }}" class="remove_item text-body"><i class="fi-rs-trash"></i></a>
                                                                </div>
                                                            </div>
            
                                                    </div>
                                                
                                                 
                                                    <!--<div class="action text-center col-md-4 py-2"  data-title="Remove"><a id="{{ $cart->id }}" class="remove_item text-body"><i class="fi-rs-trash"></i></a></div>-->
                                                </div>
                                            </div>
                                             </div>
                                            
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="text-center">
                                             <td colspan="7"><span class="text-danger">No Data Found</span></td>
                                        </div>
                                         @endif     
                                    </row>
                                <!--</table>-->
                            </div>
                            <div class="divider-2 mb-30"></div>
                            <div class="cart-action">
                                <div class="row">
                                    <div class="col-lg-6">
                                    <a href="{{url('shop')}}" class="btn "><i class="fi-rs-arrow-left mr-10"></i>Continue Shopping</a>
                                    </div>
                                    @if(sizeof($carts) > 0)
                                    <div class="col-lg-6 text-end">
                                        <form id="coupon_apply">
                                            <div class="d-flex justify-content-between">
                                                <input class="font-medium mr-15 coupon" name="coupon_code" placeholder="Enter Your Coupon Code">
                                                <button type="submit" class="btn"><i class="fi-rs-label mr-10"></i>Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="border p-md-4 cart-totals ml-30">
                                <h2 class="mb-3">Order Summary</h2>
                                <div class="table-responsive">
                                   
                                    
                                    
                                    <div style="border-radius:15px; border:1px solid #ccc; padding:10px; margin-bottom:15px; background:#f2f2f2">
                                         <h4 class=" text-end">AED {{round($total-$coupondiscount+$deliverycharges,2)}} <br><span style="font-size:12px">(inclusive of VAT)</span></h4>
                                    </div>
                                </div>
                                @if(sizeof($carts) > 0)
                                    <a href="/checkout" class="btn mb-20 w-100">Proceed To CheckOut<i class="fi-rs-sign-out ml-15"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')

<script>

        $("#coupon_apply").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "/coupon/apply",
                type: "POST",
                data: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
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
                        location.reload();
                    }
                },
            });
        }));
    $(document).on('click', '.remove_item', function () {
   var id = $(this).attr('id');
   $.ajax({
            url: "/cart/remove/"+id,
            type: "get",
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    toastr.success('Success',response["msg"])
                    location.reload()
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
});
</script>


@endsection