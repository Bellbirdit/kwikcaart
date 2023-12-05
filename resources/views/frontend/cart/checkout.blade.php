@extends('frontend/layout/master')
@section('title')
Kwikcaart | Checkout
@endsection
@section('frontend/content')

<style>
    .modal-dialog {
    position: absolute !important;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
    width: 100% !important;
    top: 82px;
    
    
}

.css-ohx365 {
    box-sizing: border-box;
    margin: 8px 0px 16px;
    min-width: 0px;
    display: flex;
    flex-direction: row;
    -webkit-box-align: center;
    align-items: center;
}

.css-1i74ox3 {
    display: inline-block;
    vertical-align: middle;
}

.css-uhdh94 {
    display: inline-block;
    width: 20px;
    height: 20px;
    background: rgb(250, 250, 250);
    border-radius: 3px;
    border: 1px solid rgb(14, 90, 167);
}
.css-mdzy61 {
    border: 0px;
    clip: rect(0px, 0px, 0px, 0px);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0px;
    position: absolute;
    white-space: nowrap;
    width: 1px;
}
.css-128czir {
    fill: none;
    stroke-width: 3px;
    stroke: rgb(255, 255, 255);
    visibility: hidden;
}

.css-1wcj84s {
    box-sizing: border-box;
    margin: 0px 0px 0px 8px;
    min-width: 0px;
    color: rgb(102, 102, 102);
    font-size: 13px;
}
.css-1g1x2nw {
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    display: flex;
}
.css-16vu25q {
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    width: 100%;
}
.css-1oh7a03 {
    width: 16px;
    height: 16px;
}
.css-1snymuy {
    box-sizing: border-box;
    margin: 0px;
    min-width: 0px;
    position: absolute;
    top: 42px;
    left: 12px;
}

.modal-content{border-radius:0;}
#myModal2.modal-header{padding:0px;}
ul.del-t {
    display: flex;
    justify-content: space-around;
}

ul.del-t li {
    background: #f2f2f2;
    padding: 10px 67px;
    border: 1px solid #ccc;
}

.modal-body .form-group {
    margin-bottom: 0;
}

/* MODAL FADE LEFT RIGHT BOTTOM */
.modal.fade:not(.in).left .modal-dialog {
	-webkit-transform: translate3d(-25%, 0, 0);
	transform: translate3d(-25%, 0, 0);
}
.modal.fade:not(.in).right .modal-dialog {
	-webkit-transform: translate3d(25%, 0, 0);
	transform: translate3d(0%, 0, 0);
}
.modal.fade:not(.in).bottom .modal-dialog {
	-webkit-transform: translate3d(0, 25%, 0);
	transform: translate3d(0, 25%, 0);
}

.modal.right .modal-dialog {
	position:absolute;
	top:0;
	right:0;
	margin:0;
}

.modal.left .modal-dialog {
	position:absolute;
	top:0;
	left:0;
	margin:0;
}

.modal.left .modal-dialog.modal-sm {
	max-width:300px;
}

.modal.left .modal-content, .modal.right .modal-content {
	min-height:100vh;
	border:0;
}

.myModal{width:500px}

input.css-zrxtm5 {
    border: 1px solid rgb(207, 207, 207);
    border-radius: 25px;
    height: 37px;
    width: 100%;
    padding: 10px 10px 10px 15px;
    margin-bottom: 12px;
    transition: all 0.3s ease 0s;
}

</style>








<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span> Shop</span>
                <span> Checkout</span>
            </div>
        </div>
    </div>
    
    
    <div class="container mb-80 mt-50">
        <div class="row">
            <div class="col-md-7">
                <div>

                <div class="border p-40 cart-totals ml-30 mb-50" >
                    <div class="row">
                        <div class="col-md-7">
                            <h2 class="heading-2 mb-2">Address</h2>
                            <p id="currentAddress" style="line-height: 1.5; font-size: 13px;" data-id="@if(isset($defaultAddress) and !empty(isset($defaultAddress))){{ $defaultAddress->id }}@endif">
                                @if(Auth::check()){{ Auth::user()->name }}@endif <br>
                                @if(Auth::check()){{ Auth::user()->contact }}@endif <br>
                                @if(Auth::check()){{ Auth::user()->email }}@endif <br>
                                @if(isset($defaultAddress) and !empty(isset($defaultAddress)))
                                #{{ $defaultAddress->flat_name }} {{ $defaultAddress->address }} <br><br>
                                @endif
                                
                            </p>
                            @if(isset($defaultAddress) and !empty(isset($defaultAddress)))
                                <button class="btn btn-dark mt-1" onclick="listAddress()">Change Address</button>
                            @else
                                <button class="btn btn-dark mt-1" onclick="listAddress()">Add Address</button>
                            @endif
                            <button class="btn btn-dark mt-1" onclick="selectSlot()">Select Delivery Method</button>    
                        </div>
                        <div class="col-md-5">
                            <p id="timeslot" style="line-height: 1.5; font-size: 13px;">
                            </p>
                            
                                
                        </div>
                    </div>
                    
                    
                    
                    
                </div>
                    
                    
                   <div class="border p-40 cart-totals ml-30 mb-50">
                    
                    <div class="d-flex align-items-end justify-content-between mb-30">
                        <h2 class="heading-2 mb-2">Order Detail</h2>
                    </div>
                    <div class="table-responsive order_table checkout overflow-hidden">
                        <table class="table no-border overflow-hidden">
                            <tbody style="overflow-y: hidden !important;">
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                                @php
                            $total = 0;
                            @endphp
                            @foreach($carts as $cart)
                                    <tr>
                                    <td class="image product-thumbnail" width="70px"> <img src="{{ asset('uploads/files/'.$cart->image) }}">
                                    </td>
                                    <td>
                                        <h6 class="w-100 mb-" style="display:block"><a href="javascript:;" class="text-heading">{{ $cart->name }}</a></h6>
                                    </td>
                                    <td>
                                        <h6 class="text-muted pl-20 pr-20">x {{ $cart->quantity }}</h6>
                                    </td>
                                    <td>
                                        <h4 class="text-brand">
                                            <?php 
                                            $product =  App\Models\Product::where('id',$cart->product_id)->first(); 
                                            $priceArray = $product->get_deal_price();
                                            $price = $priceArray['price'];
                                            $total += $price*$cart->quantity;
                                        ?>
                                        {{ round($price * $cart->quantity,2) }}
                                        </h4>
                                    </td>
                                </tr>
                                @endforeach
                                     
                            </tbody>
                        </table>
                    </div>
                    
                        
                    
                </div>
                
               
                
              
                    
                </div>
                
            </div>
             <div class="col-md-5">
                 @php
                 $shippingcharges = 0;
                 $total = 0;
                 $deliverylimit = DB::table('web_settings')->pluck('delivery_limit')->first();
                 @endphp
                 @foreach($carts as $cart)
                    @php
                    $subtotal = App\Models\Cart::where('user_id',
                    auth()->user()->id)->where('store_id',
                    Session::get('store_id'))->where('status',
                    'pending')->sum('quantity_price');
                    @endphp
                @endforeach     
                @foreach($carts as $cart)
                    @php
                    $vat =  App\Models\Product::where('id',$cart->product_id)->first();
                    $priceArray = $vat->get_deal_price();
                    $price = $priceArray['price'];
                    $total += $price*$cart->quantity;
                    $shipping = DB::table('web_settings')->pluck('standard_delivery')->first();
                    $deliverylimit = DB::table('web_settings')->pluck('delivery_limit')->first();
                    $dcapplicable = DB::table('web_settings')->pluck('delivery_applicable')->first();
                    if($total >= $dcapplicable){
                    $shippingcharges = 0;
                    }else{
                    $shippingcharges = $shipping;
                    }
                    @endphp
                @endforeach 
                @php
                    $coupondiscount = Session::get('coupondiscount');
                @endphp
                <div class="border p-40 cart-totals ml-30 mb-50">
                    <h2 class="heading-2 mb-2">Order Summary</h2>
                    <div class="d-flex justify-content-between mb-2">
                            <input class="font-medium mr-15 coupon" id="coupon_code" placeholder="Enter Your Coupon Code">
                            <button type="button" id="coupon_apply" class="btn"><i class="fi-rs-label mr-10"></i>Apply</button>
                        </div>
                        <hr>
                    <div class="check">
                        <div>Subtotal</div>
                        <div> AED {{ round($total,2) }}</div>
                    </div>
                    @if($shippingcharges > 0)
                    <div class="check">
                        <div>Delivery and Service Fees</div>
                        <div> AED {{ $shippingcharges }}</div>
                    </div>
                    @endif
                    @if($coupondiscount > 0)
                    <div class="check">
                        <div>Coupon Discount</div>
                        <div> AED {{ $coupondiscount }} ({{ Session::get('coupon_id') }})</div>
                    </div>
                    @endif
                    <hr>
                    
                    <div class="check">
                        <div>Total amount due</div>
                        <div> AED {{ round($total-$coupondiscount+$shippingcharges,2) }}</div>
                    </div>
                    
                    <hr>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" checked="">
                            <span>By placing this order, I confirm that I have read and agreed with the <a href="#" target="_blank" style="color:#0E5AA7; text-decoration:none">Terms &amp; Conditions</a>.</span>
                        </label>
                       
                    </div>
                    
                    <form action="{{ route('place.order') }}" method="post" class="require-validation" data-cc-on-file="false" id="payment-form" autocomplete="off">
                        <div class="custome-radio">
                            <input type="hidden" name="" class="" id="is_stripe_yes" value="">
                            <input class="form-check-input payment_online" required="" type="radio" name="payment_option" id="exampleRadios4">
                            <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">Online payment</label>
                        </div>
                        <div class="custome-radio">
                            <input type="hidden" name="" class="" id="is_stripe_no" value="">
                            <input class="form-check-input payment_online payment_option" required="" type="radio" name="payment_option" id="exampleRadios5" value="no">
                            <label class="form-check-label" for="exampleRadios5" data-bs-toggle="collapse" data-target="#paypal" aria-controls="paypal">Cash on delivery</label>
                        </div>
                        
                        <div id="buttonPaymentListContainer" style="display:none;">
                            <button type="button" id="checkoutEmbedded" class="btn btn-lg btn-block btn-primary" disabled="disabled">
                                Loading...
                            </button>
                            <button type="button" id="checkoutSidebar" class="btn btn-lg btn-block btn-primary" disabled="disabled">
                                Loading...
                            </button>
                        </div>
                        <button type="button" class="btn w-100" data-amount="{{ round($total-$coupondiscount+$shippingcharges,2) }}" id="order-now" onclick="orderNow()">PLACE ORDER (AED {{ round($total-$coupondiscount+$shippingcharges,2) }})</button>
                    </form>
                    
                    <form id="authForm" action="https://testsecureacceptance.cybersource.com/silent/pay" method="post" target="">
                        <input type="hidden" id="capturecontext" name="capture_context" value="" />
                        <input type="hidden" id="transientToken" name="transient_token" />
                    </form>
                </div>
                
               
        </div>
    </div>
   
</main>



<!-- The Modal -->
<div class="modal fade right" id="myModal3" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
          <div>
        <h4 class="modal-title">Select Address</h4>
            
        </div>
        <button class="btn btn-normal" data-bs-toggle="modal" data-bs-target="#myModal2">Add new Address</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="addresses">
            
            
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



<!-- The Modal2-->
 <div class="modal fade right" id="myModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">


      <!-- Modal Header -->
      <div class="modal-header">
          
        <div class="form-group col-lg-12 " style="position: relative;">
            <div class="form-group col-lg-11 d-flex">
          <h2 class="heading-2">Add New Address</h2>
          
          </div>
        </div>
          <button type="button" class="btn-close mr-3" data-bs-dismiss="modal"></button>            
      </div>

      <!-- Modal body -->
      <div class="modal-body">
          <div class="css-16vu25q">
                <input type="text" placeholder="Enter Location" name="address" id="pac-input-add-address" class="css-zrxtm5 pac-target-input" autocomplete="off">
            </div>
            <div id="map" width="100%" height="300">
                
            </div>
            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.2743369362074!2d55.17234742500084!3d25.22768332769138!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f40e6bde69adb%3A0xbcc3411dcc9a1f60!2sEmirates!5e0!3m2!1sen!2sin!4v1696584058995!5m2!1sen!2sin" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>-->
            
          <h3 class="mt-3">Address Information</h3>
       <div class="form-group col-lg-12">
                                <input type="text" required="" class="street_name" value="" name="street_name" placeholder="Street Name *">
                            </div>
          <div class="form-group col-lg-12">
                                <input type="text" required="" class="building_name" value="" name="building_name" placeholder="Building Name *">
                            </div>
              <div class="form-group col-lg-12">
                                <input type="text" required="" class="apartment" value="" name="apartment" placeholder="Apartment / Villa Number *">
                            </div>
                  <div class="form-group col-lg-12">
                                <input type="text" required="" class="landmark" value="" name="landmark" placeholder="Landmark">
                            </div>
                    
                      <div class="form-group col-lg-12">
                                <input type="text" required="" class="delivery_instructions" value="" name="delivery_instructions" placeholder=" Delivery Instructions">
                            </div>
                            <div class="row ">
                             <div class=" col-lg-4">
                            <h2 class="heading-2 mb-3 mt-3">Address Type</h2>
                         </div>   
                         <div class="col-lg-8 mt-3" style="text-align: right;">
                            <button type="button" class="btn btn-outline-primary addresstype">Home</button>
                            <button type="button" class="btn btn-outline-primary addresstype">Office</button>
                            <button type="button" class="btn btn-outline-primary addresstype">Other</button>
                        </div>   
                    </div>
                             
                            
    <!--    <div class="form-group col-lg-12 mt-2" style="display:flex; justify-content: space-between;">-->
    <!--                            <span>Set this address as default</span>-->
    <!--                             <div class="form-check form-switch">-->
    <!--  <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode" value="yes" checked>-->
    <!--</div>-->
    <!--                        </div>-->
        
        
      </div>
      
      
     

      <!-- Modal footer -->
      <div class="modal-footer">
         <button type="button" class="btn btn-danger" onclick="saveAddress()" >Submit</button>
      </div>

    </div>
  </div>
</div>






<!-- The Modal -->
 <div class="modal fade right" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
          <!--<form style="width:90%">-->
                 <div class="row">
                            <div class="form-group col-lg-12">
                             
                              <ul class="nav nav-pills heade" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home Delivery</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Collect From Store</button>
  </li>
  
</ul>
                                  
                                            
                                
                  
                            </div>
                           
                            
                            
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
                 
                    
                        
                        
                        
                        <div class="row">
                             <div class="form-group col-lg-12">
                                 
                              <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
      
                                         <ul class="nav nav-pills mb-3" id="pills-tab1" role="tablist">
                                             @php
                                             $key = 0;
                                             @endphp
                                             @foreach ($shippingschedules as  $schedule)
                                
                                        @if ($today->lessThanOrEqualTo($schedule->date))
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link @if($key==0) active @endif" id="{{ date('FdY', strtotime($schedule->date)) }}" data-bs-toggle="pill" data-bs-target="#{{ date('FdY', strtotime($schedule->date)) }}-tab" type="button" role="tab" aria-controls="pills-home1" aria-selected="true">{{ date("d F, Y", strtotime($schedule->date)) }}</button>
                                          </li>
                                        @php
                                             $key = 1;
                                             @endphp
                                        @endif
                                        @endforeach
                                        </ul>
                                        
                                        
                                        
                                        <div class="tab-content" id="pills-tabContent1">
                                            
                                    @foreach ($shippingschedules as $key => $schedule)
                                
                                    @if ($today->lessThanOrEqualTo($schedule->date))
                                   
                                     <div class="tab-pane fade  @if($key == 0) show active @endif" id="{{ date('FdY', strtotime($schedule->date)) }}-tab" role="tabpanel" aria-labelledby="{{ date('FdY', strtotime($schedule->date)) }}">
      
        <div class="del-time">
                                     
                                 <ul>
                                     
                        
                                      
                                    @foreach ($schedule->shippingTimes as $shipping)
                                    
                                        
                                    @php
                                    $currentTime = \Carbon\Carbon::now()->timestamp;
                                    $startTime = strtotime($schedule->date." ".$shipping->start_time);
                                    
                                    @endphp
                                    
                                                @if ($currentTime < $startTime && $shipping->count > 0)
                                                <li data-id="{{ $shipping->id }}-s" data-time="{{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}" data-datep="{{ date('d F, Y', strtotime($schedule->date)) }}" data-date="{{ $schedule->date }}" data-sid="{{ $schedule->id }}">
                                                     {{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}
                                                     <span>Open</span>
                                                 </li>
                                                @else
                                                <li data-id="{{ $shipping->id }}-s" data-time="{{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}" data-datep="{{ date('d F, Y', strtotime($schedule->date)) }}" data-date="{{ $schedule->date }}" data-sid="{{ $schedule->id }}" class="disabled">
                                                     {{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}
                                                     <span>Close</span>
                                                 </li>
                                                @endif
                                    @endforeach
                                    </ul>
                               
                            </div>
      
      
      
  </div>
                                    @endif
                                    @endforeach
                                     
                                     
                                     
            
      
  </div>
</div>
      
      
      

  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                       <ul class="nav nav-pills mb-3" id="pills-tab1" role="tablist">
                                             @php
                                             $key = 0;
                                             @endphp
                                             @foreach ($pickupchedules as  $schedule)
                                
                                        @if ($today->lessThanOrEqualTo($schedule->date))
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link @if($key==0) active @endif" id="{{ date('FdY', strtotime($schedule->date)) }}profile" data-bs-toggle="pill" data-bs-target="#{{ date('FdY', strtotime($schedule->date)) }}-profile" type="button" role="tab" aria-controls="pills-home1" aria-selected="true">{{ date("d F, Y", strtotime($schedule->date)) }}</button>
                                          </li>
                                        @php
                                             $key = 1;
                                             @endphp
                                        @endif
                                        @endforeach
                                        </ul>
                                        
                                        
                                        
                                        <div class="tab-content" id="pills-tabContent1">
                                            
                                    @foreach ($pickupchedules as $key => $schedule)
                                
                                    @if ($today->lessThanOrEqualTo($schedule->date))
                                   
                                     <div class="tab-pane fade  @if($key == 0) show active @endif" id="{{ date('FdY', strtotime($schedule->date)) }}-profile" role="tabpanel" aria-labelledby="{{ date('FdY', strtotime($schedule->date)) }}profile">
      
        <div class="del-time">
                                     
                                 <ul>
                                     
                        
                                      
                                    @foreach ($schedule->PickupTimes as $shipping)
                                    
                                        
                                    @php
                                    $currentTime = \Carbon\Carbon::now()->timestamp;
                                    $startTime = strtotime($schedule->date." ".$shipping->start_time);
                                    
                                    @endphp
                                    
                                                @if ($currentTime < $startTime && $shipping->count > 0)
                                                <li data-id="{{ $shipping->id }}-p" data-time="{{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}" data-datep="{{ date('d F, Y', strtotime($schedule->date)) }}" data-date="{{ $schedule->date }}" data-sid="{{ $schedule->id }}">
                                                     {{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}
                                                     <span>Open</span>
                                                 </li>
                                                @else
                                                <li data-id="{{ $shipping->id }}-p" data-time="{{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}" data-datep="{{ date('d F, Y', strtotime($schedule->date)) }}" data-date="{{ $schedule->date }}" data-sid="{{ $schedule->id }}" class="disabled">
                                                     {{ date('h:i A', strtotime($shipping->start_time)) }} To {{ date('h:i A', strtotime($shipping->end_time)) }}
                                                     <span>Close</span>
                                                 </li>
                                                @endif
                                    @endforeach
                                    </ul>
                               
                            </div>
      
      
      
  </div>
                                    @endif
                                    @endforeach
                                     
                                     
                                     
            
      
  </div>
  </div> 
  </div>
  
</div>
                 

                                 
                        </div>
                        
                      
               
            <!--</form>-->
      </div>

      <div class="modal-footer text-center">
        <button type="button" class="btn btn-danger" id="myBtn2" data-bs-toggle="modal" data-bs-target="#myModal" style="">Submit</button>
      </div>

    </div>
  </div>
  
</div>
  
  
  
  





@endsection
@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .disabled{
        background: #ccc;
            cursor: not-allowed !important;
    }
    .form-group{
            margin-bottom: unset !important;
    }
    .invoice-1 .invoice-info-buttom .table .invoice-1 .invoice-info-buttom .table tr, .table tr{
            border: inherit !important;
            border-bottom: 1px solid #e9ecef !important;
    }
    .order_table table .product-thumbnail img{
        max-width: 45px !important;
        border: inherit !important;
    }
</style>
<script>
  $(document).ready(function(){
    $("#myModal").modal("hide");
    $("#myModal2").modal("hide");
    $("#myModal3").modal("hide");
      
    $("#myBtn").click(function(){
        
    $("#myModal2").modal("hide");
      $("#myModal").modal("show");
     
    });
    
       $("#myBtn2").click(function(){
        
    $("#myModal2").modal("hide");
      $("#myModal").modal("hide");
     
    });
    
    
    // $("#myModal").on('hide.bs.modal', function(){
    //   alert('The modal is about to be hidden.');
    // });
    
    $(".btn-outline-primary").click(function(){
        $(this).addClass("btn-primary")
    })
    
    $(".del-time ul li").click(function(){
        if(!$(this).hasClass("disabled")){
            $(".del-time ul li").removeClass("liactive");
            $(this).addClass("liactive");
            let id = $(this).attr("data-id");
            let sid = $(this).attr("data-sid");
            let date = $(this).attr("data-date");
            let datep = $(this).attr("data-datep");
            let time = $(this).attr("data-time");
            // console.log(id, sid, datep, date, time)
            let span = `
            <br>
            <b class="fw-bold">Date: </b>  ${datep} <br>
            <b class="fw-bold">Time: </b>   ${time} <br>`;
            $("#timeslot").html(span);
            $("#timeslot").attr("data-sid", sid);
            $("#timeslot").attr("data-id", id);
            $("#timeslot").attr("data-time", time);
            $("#timeslot").attr("data-date", date);
        }
        
    })
    
  });
  $(".addresstype").click(function(){
    $(".addresstype").removeClass("active");
    $(this).addClass("active");
  });
  </script>
 
  




<script>
function orderNow(){
    let tid = $("#timeslot").attr("data-id");
    let time = $("#timeslot").attr("data-time");
    let date = $("#timeslot").attr("data-date");
    let address = $("#currentAddress").attr("data-id");
    let amount = $("#order-now").attr("data-amount");
    
    if(parseInt(amount) <= {{$deliverylimit}}){
        toastr.error('Please select products more then AED {{$deliverylimit}}');
        return;
    }
    if(tid == undefined || tid == ""){
        toastr.error('Please select timeslot');
        return;
    }
    if(address == undefined || address == ""){
        toastr.error('Please select address');
        return;
    }
    
    
    $.ajax({
        url: "/place/order",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          time_id: tid,
          address_id: address,
          pick_time: time,
          pick_date: date
        },
        success: function(response) {
            console.log(response);
            window.location.href = "/success";
        },
    });
    
}

function saveAddress(){
        var address = $("#pac-input-add-address").val()
        var street_name = $(".street_name").val()
        var building_name = $(".building_name").val()
        var apartment = $(".apartment").val()
        var landmark = $(".landmark").val()
        var delivery_instructions = $(".delivery_instructions").val()
        var addresstype = "";
        if($(".addresstype.active").length){
            addresstype = $(".addresstype.active").text()    
        }
        
        
        if (address == '') {
            toastr.error('Please enter address');
            return;
        }
        if (street_name == '') {
            toastr.error('Please enter street name');
            return;
        }
        if (building_name == '') {
            toastr.error('Please enter building name');
            return;
        }
        if (apartment == '') {
            toastr.error('Please enter apartment/number');
            return;
        }
        if (landmark == '') {
            toastr.error('Please enter landmark');
            return;
        }
        if (addresstype == '') {
            toastr.error('Please select address type');
            return;
        }
        $.ajax({
        url: "/add_address",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
           building_name: building_name,
           street_name: street_name,
           flat_name: apartment,
           landmark: landmark,
           delivery_instructions: delivery_instructions,
           address_type: addresstype,
           address: address
        },
        success: function(response) {
            $("#myModal3").modal('hide');
            $("#myModal2").modal('hide');
            $("#myModal").modal("show");
            currentAddress();
        },
    });
}

function currentAddress(){
    $.ajax({
        url: "/current_address",
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            let address = `<b class="fw-bold">Name: </b>@if(Auth::check()){{ Auth::user()->name }}@endif <br>
            <b class="fw-bold">Address: </b>  ${response.address.address} <br>
            <b class="fw-bold">Street: </b>   ${response.address.street_name} <br>
            <b class="fw-bold">Building: </b>   ${response.address.building_name}<br>
            <b class="fw-bold">Flat: </b>   ${response.address.flat_name} <br>
            <b class="fw-bold">Landmark: </b>   ${response.address.landmark} <br>
            <b class="fw-bold">Type: </b>   ${response.address.address_type} <br>
            <b class="fw-bold">Phone: </b> @if(Auth::check()){{ Auth::user()->contact }}@endif <br>
            <b class="fw-bold">Email: </b>@if(Auth::check()){{ Auth::user()->email }}@endif <br>`
            $("body").find("#currentAddress").html(address);
            $("body").find("#currentAddress").attr("data-id", response.address.id);
        },
    });
    
    
}

function selectSlot(){
    $("#myModal").modal("show");
}

function listAddress(){
    $.ajax({
        url: "/list_address",
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $("#addresses").html(' ');
            response.addresses.forEach(function(address){
                let addressx = `<div class="border p-20 cart-totals mt-2">
                    <div class="form-check">
                      <input class="form-check-input default_addresschange" ${address.is_default=="1"?"checked":""} data-id="${address.id}" type="radio" name="flexRadioDefault" id="flexRadioDefault1${address.id}"/>
                      <label class="form-check-label" for="flexRadioDefault1">
                            <P>Name : @if(Auth::check()){{ Auth::user()->name }}@endif</P>
                            <p>Address:  ${address.address}</p>
                            <p>Street:  ${address.street_name}</p>
                            <p>Building:  ${address.building_name}</p>
                            <p>Flat:  ${address.flat_name}</p>
                            <p>Landmark:  ${address.landmark}</p>
                            <p>Type:  ${address.address_type}</p>
                            <p>Phone: @if(Auth::check()){{ Auth::user()->contact }}@endif</p>
                            <p>Email: @if(Auth::check()){{ Auth::user()->email }}@endif</p> </label>
                    </div>
                </div>`;
                $("#addresses").append(addressx);
            });
            $("#myModal3").modal('show');
            
        },
    });
}

$(document).on("click", ".default_addresschange", function(){
   
    let id = $(this).attr("data-id");
    $.ajax({
        url: "/default_address?id="+id,
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $("#myModal3").modal('hide');
            $("#myModal").modal("show");
            currentAddress();
        },
    });
});


$("#coupon_apply").on('click', (function(e) {
            e.preventDefault();
            var coupon_code = $("#coupon_code").val();
            // alert(coupon_code);
            $.ajax({
                url: "/coupon/apply",
                type: "POST",
                data: {"coupon_code": coupon_code},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // dataType: "JSON",
                // processData: false,
                // contentType: false,
                // cache: false,
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
    $("#deliveryOptions").change(function() {
        var delivery_option = $(this).val();
        if (delivery_option == "standerd_delivery") {
            $("#noscharges").css('display', 'contents');
            $("#noschargest").css('display', 'none');
            $("#yschargest").css('display', 'contents');
            $("#sdlivery").css('display', 'flex');
            $("#sPickup").css('display', 'none');

        } else if (delivery_option == "self_pickup") {
            $("#sPickup").css('display', 'flex');
            $("#sdlivery").css('display', 'none');
            $("#noscharges").css('display', 'none');
            $("#noschargest").css('display', 'contents');
            $("#yschargest").css('display', 'none');

        } else {
            $("#sdlivery").css('display', 'none');
            $("#sPickup").css('display', 'none');
            $("#noscharges").css('display', 'contents');
            $("#noschargest").css('display', 'none');
            $("#yschargest").css('display', 'flex');


        }
    })
</script>
<script type="text/javascript">
    $(function() {
        $("#payment-method").on('change', function() {
            if ($(this).is(':checked')) {
                $('.pay-with-stripe').slideDown('slow');
            } else {
                $('.pay-with-stripe').hide('slow');
            }
        });

        $(".place_order").on('click', function() {
            toastr.info('Please Login to Place Order')
            $("#loginform").addClass('show')
        });
    });
    $(document).ready(function() {
        $('.card-number').inputmask('9999-9999-9999-9999');
        $('.card-expiry-month').inputmask('99');
        $('.card-expiry-year').inputmask('9999');
        $('.card-cvc').inputmask('999');
    });
</script>
<!-- stripe payment -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    // $(function() {
    //     $(document).on("change", '.payment_online', function() {

    //         if ($(this).val() == "no") {
    //             document.getElementById('is_stripe_no').value = "no";
    //             $("#payment_field_show").hide();
    //             $('.submit_form').attr('id');


    //         } else if ($(this).val() == "wallet") {
    //             document.getElementById('is_stripe_no').value = "no";
    //             $("#payment_field_show").hide();
    //             $('.submit_form').attr('id');
    //         } else {
    //             document.getElementById('is_stripe_yes').value = "yes";
    //             $('.submit_form').removeAttr('id');
    //             $("#payment_field_show").css('display', 'flex');



    //         }
    //     })
    // });
</script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<style>
    #map{
        width: 100%;
        height: 300px;
    }
    .pac-container{
        z-index: 100001;
    }
</style>

<script>

function initAutocomplete() {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 25.2048, lng: 55.2708 },
    zoom: 13,
    mapTypeId: "roadmap",
  });
  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input-add-address");
  const searchBox = new google.maps.places.SearchBox(input);

//   map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });

  let markers = [];

  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();

    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }
      $(".street_name").val('');
      $(".building_name").val('');
      $(".landmark").val('');
      

        for (const component of place.address_components) {

            const componentType = component.types[0];
            console.log(component, componentType);
            switch (componentType) {
                case "route": {
                    $(".street_name").val(component.long_name);
                    break;
                }
                case "premise": {
                    $(".building_name").val(component.long_name);
                    break;
                }
                case "neighborhood": {
                    $(".landmark").val(component.long_name);
                    break;
                }
            }
        }

      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

      // Create a marker for each place.
      markers.push(
        new google.maps.Marker({
          map,
          icon,
          title: place.name,
          position: place.geometry.location,
        }),
      );
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}

window.initAutocomplete = initAutocomplete;
// initAutocomplete();
    // let autocomplete;
    // let address1Field;
    // let address2Field;
    // let postalField;

    // function initAutocomplete() {
    //     address1Field = document.querySelector("#pac-input-add-address");

    //     address2Field = document.querySelector("#address2");
    //     postalField = document.querySelector("#postcode");

    //     autocomplete = new google.maps.places.Autocomplete(address1Field, {
    //         componentRestrictions: {
    //             country: ["AE"]
    //         },
    //         fields: ["address_components", "geometry"],
    //         types: ["address"],
    //     });
    //     address1Field.focus();

    //     autocomplete.addListener("place_changed", fillInAddress);
    // }

    // function fillInAddress() {

    //     const place = autocomplete.getPlace();
    //     let address1 = "";
    //     let postcode = "";

    //     for (const component of place.address_components) {

    //         const componentType = component.types[0];
    //         console.log(component);
    //         // switch (componentType) {
    //         //     case "street_number": {
    //         //         address1 = `${component.long_name} ${address1}`;
    //         //         break;
    //         //     }

    //         //     case "route": {
    //         //         address1 += component.long_name;
    //         //         break;
    //         //     }

    //         //     case "postal_code": {
    //         //         postcode = `${component.long_name}${postcode}`;
    //         //         break;
    //         //     }
    //         //     case "postal_code_suffix": {
    //         //         postcode = `${postcode}-${component.long_name}`;
    //         //         break;
    //         //     }

    //         //     case "administrative_area_level_1": {
    //         //         document.querySelector("#map_state").value = component.long_name;
    //         //         setState()
    //         //         break;
    //         //     }

    //         //     case "locality":
    //         //         document.querySelector("#locality").value = component.long_name;
    //         //         break;

    //         //     case "country":
    //         //         // document.querySelector("#country").value = component.short_name;
    //         //         $("#country").val(component.short_name)
    //         //         break;


    //         // }
    //     }

    //     // address1Field.value = address1;
    //     // postalField.value = postcode;

    // }
    // window.initAutocomplete = initAutocomplete;
    // // initAutocomplete();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLgkbCBN1poChfbWCl7i3yIK_mFI8ORyU&callback=initAutocomplete&libraries=places&v=weekly" defer></script>
<script type="text/javascript">
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error').text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
    $(document).on('click', '#exampleRadios4', function(e) {
        console.log('remove')
        $('.submit_form').removeAttr('id');
    });
    $(document).on('click', '#exampleRadios6', function(e) {
        $('.submit_form').attr('id', 'submit_form');
    });
    $(document).on('click', '#exampleRadios5', function(e) {
        console.log('add')
        $('.submit_form').attr('id', 'submit_form');
    });
    $(document).on('click', '#submit_form', function(e) {
        e.preventDefault();
        var payment_option = $("input[name='payment_option']:checked").val()
        var delivery_option = $('.delivery_option').val();
        var pick_time = $("input[name='pick_time']:checked").val();
        var pick_date = $("input[name='pick_time']:checked").attr('date');
        var first_name = $('.first_name').val();
        var address = $('.address').val();
        var phone = $('.phone').val();
        var email = $('.email').val();
        if (first_name == '') {
            toastr.error('Please enter full name');
            return;
        }
        if (phone == '') {
            $(this).prop('checked', false); 
            toastr.error('Please enter your phone number');
            return;
        } else if (email == '') {
            $(this).prop('checked', false); 
            toastr.error('Please enter your email');
            return;
        }
        if (address == '') {
            $(this).prop('checked', false); 
            toastr.error('Please enter shipping address');
            return;
        }
        if (delivery_option == '') {
            $(this).prop('checked', false); 
            toastr.error('Please select delivery option');
            return;
        }
        if (pick_time == '') {
            $(this).prop('checked', false); 
            toastr.error('Please select delivery time slot');
            return;
        }
        if (pick_date == '') {
            $(this).prop('checked', false); 
            toastr.error('Please select delivery time slot');
            return;
        }
        if (payment_option == 'no' || payment_option == 'wallet') {
            e.preventDefault();
            var first_name = $('.first_name').val();
            var address = $('.address').val();
            var phone = $('.phone').val();
            var email = $('.email').val();
            var delivery_option = $('.delivery_option').val();
            var additional_information = $('.additional_information').text();

            $.ajax({
                url: "/place/order",
                type: "post",
                data: {
                    first_name: first_name,
                    address: address,
                    phone: phone,
                    email: email,
                    delivery_option: delivery_option,
                    additional_information: additional_information,
                    payment_option: payment_option,
                    pick_date: pick_date,
                    pick_time: pick_time
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                beforeSend: function() {
                    $("#submit_form").attr('disabled', true);
                    $(".fa-spins").css('display', 'inline-block');
                },
                complete: function() {
                    $("#submit_form").attr('disabled', false);
                    $(".fa-spins").css('display', 'none');
                },
                success: function(response) {
                    console.log(response)
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                        window.location.href = "/success";

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });

    $(document).on('submit', '#login_form', function(e) {
        e.preventDefault();
        $.ajax({
            url: "/api/login",
            type: "post",
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
                console.log(response)
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $("#login_form")[0].reset();
                    location.reload()
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
<script src="https://apitest.cybersource.com/up/v1/assets/0.10.0/SecureAcceptance.js"></script>



<script>
    $(document).on("change", '.payment_online', function(e) {
        e.preventDefault();
        var payment_option = $("input[name='payment_option']:checked").val()
        let tid = $("#timeslot").attr("data-id");
        let time = $("#timeslot").attr("data-time");
        let date = $("#timeslot").attr("data-date");
        let address = $("#currentAddress").attr("data-id");
        let amount = $("#order-now").attr("data-amount");
        $(this).prop('checked', false); 
        if(parseInt(amount) <= {{$deliverylimit}}){
            toastr.error('Please select products more then AED {{$deliverylimit}}');
            return;
        }
        if(tid == undefined || tid == ""){
            toastr.error('Please select timeslot');
            return;
        }
        if(address == undefined || address == ""){
            toastr.error('Please select address');
            return;
        }
        $(this).prop('checked', true); 
        if (payment_option != 'no' && payment_option != 'wallet') {
            $("#buttonPaymentListContainer").show();
            $("#order-now").hide();
            $(".checkout-form").hide();
            e.preventDefault();

            $.ajax({
                url: "/place/payment_token",
                type: "post",
                data: {
                    time_id: tid,
                    address_id: address,
                    pick_time: time,
                    pick_date: date,
                    amount: "{{ round($total-$coupondiscount+$shippingcharges,2) }}"
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                beforeSend: function() {
                    $("#submit_form").attr('disabled', true);
                    $(".fa-spins").css('display', 'inline-block');
                },
                complete: function() {
                    $("#submit_form").attr('disabled', false);
                    $(".fa-spins").css('display', 'none');
                },
                success: function(response) {
                    try{
                        let js = JSON.parse(response);
                        if(js.message != undefined){
                            alert(js.message);
                        }
                    }catch(e){
                        
                    }
                    var authForm = document.getElementById("authForm");
                    var transientToken = document.getElementById("transientToken");
                    var cc = document.getElementById("capturecontext").value = response.responseText;
                    
                     var showArgs = {
                        containers: {
                            paymentSelection: "#buttonPaymentListContainer"
                            //paymentScreen: "#embeddedPaymentContainer"
                        }
                    };
                    Accept(cc)
                        .then(function (accept) {
                            //return accept.unifiedPayments(false);
                            return accept.unifiedPayments();
                        })
                        .then(function (up) {
                            return up.show(showArgs);
                        })
                        .then(function (tt) {
                            transientToken.value = tt;
                            //merchant should handle tt accordingly for example submit the tt for processing
                            authForm.submit();
                        }).catch(function (error) {
                            //merchant logic for handling issues
                            console.log(error, "error");
                            //alert("something went wrong");
                        });
                },
                error: function(error) {
                    console.log("error", error);
                    var authForm = document.getElementById("authForm");
                    var transientToken = document.getElementById("transientToken");
                    var cc = document.getElementById("capturecontext").value = error.responseText;
                    
                     var showArgs = {
                        containers: {
                            paymentSelection: "#buttonPaymentListContainer"
                            //paymentScreen: "#embeddedPaymentContainer"
                        }
                    };
                    Accept(cc)
                        .then(function (accept) {
                            //return accept.unifiedPayments(false);
                            return accept.unifiedPayments();
                        })
                        .then(function (up) {
                            return up.show(showArgs);
                        })
                        .then(function (tt) {
                            transientToken.value = tt;
                            //merchant should handle tt accordingly for example submit the tt for processing
                            authForm.submit();
                        }).catch(function (error) {
                            //merchant logic for handling issues
                            console.log(error, "error");
                            //alert("something went wrong");
                        });
                }
            });
        } else {
            $("#buttonPaymentListContainer").hide();
            $("#order-now").show();
        }
        
        



    });
    




</script>
@endsection



<style>
    @media (min-width: 576px)
.modal-sm {
    max-width: auto;
}
</style>