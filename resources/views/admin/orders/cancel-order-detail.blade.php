@extends('layout/master')
@section('title')
Kwikcaart | Return Order Detail
@endsection
@section('content')
@if(isset($orders) && !empty($orders))
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Return Order detail</h2>
            <p>Details for Order ID:{{App\Models\Order::where('id',$orders->order_id)->pluck('order_number')->first()}} </p>
        </div>
    </div>
    <div class="card">
        <header class="card-header">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                    <span> <i class="material-icons md-calendar_today"></i> <b>{{ \Carbon\Carbon::parse($orders->created_at)->isoFormat('MMM Do YYYY')}} </b>
                    </span> <br />
                    <small class="text-muted">Order ID:{{App\Models\Order::where('id',$orders->order_id)->pluck('order_number')->first()}} </small>
                </div>
                <!-- <div class="col-lg-6 col-md-6 mb-lg-0 mb-15 text-md-center">
                    @if($orders->delivery_option == 'express_delivery')
                        <span> <i class="material-icons"></i> <b>Express Delivery<span>
                     @elseif($orders->delivery_option == 'self_pickup')
                        <span> <i class="material-icons"></i> <b>Self Pickup </b>
                    </span> <br />
                    <small class="text-muted">Date: {{$orders->pick_date}}</small><br />
                    <small class="text-muted">Time: {{$orders->pick_time}}</small>
                    @elseif($orders->delivery_option == 'standerd_delivery')
                    <span> <i class="material-icons"></i> <b>Standard Delivery </b>
                    </span> <br />
                    <small class="text-muted">Date: {{$orders->pick_date}}</small><br />
                    <small class="text-muted">Time: {{$orders->pick_time}}</small>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 mb-lg-0 mb-15 text-md-end">
                    <a href="/order/download/{{$orders->id}}" class="btn btn-primary"><i class="fa fa-file-pdf"></i></a>
                </div> -->
                <div class="col-lg-4 col-md-4 mb-lg-0 mb-15 text-md-end">
                <span> <b>Return Reason: </b>{{$orders->reason}}
                    </span>
                </div>
            </div>
        </header>
        <!-- card-header end// -->
        <div class="card-body">
            <div class="row mb-50 mt-20 order-info-wrap">
                <div class="col-md-4">
                    <article class="icontext align-items-start">
                        <span class="icon icon-sm rounded-circle bg-primary-light">
                            <i class="text-primary material-icons md-person"></i>
                        </span>
                        <div class="text">
                            <h6 class="mb-1">Customer</h6>
                            <p class="mb-1">
                            {{App\Models\Order::where('id',$orders->order_id)->pluck('first_name')->first()}} <br />
                            {{App\Models\Order::where('id',$orders->order_id)->pluck('email')->first()}} <br />
                            {{App\Models\Order::where('id',$orders->order_id)->pluck('phone')->first()}}
                            </p>
                            <!-- <a href="#">View profile</a> -->
                        </div>
                    </article>
                </div>
                <!-- col// -->
                <div class="col-md-4">
                    <article class="icontext align-items-start">
                        <span class="icon icon-sm rounded-circle bg-primary-light">
                            <i class="text-primary material-icons md-local_shipping"></i>
                        </span>
                        <div class="text">
                            <h6 class="mb-1">Order info</h6>
                            <p class="mb-1">
                                Shipping: {{App\Models\Order::where('id',$orders->order_id)->pluck('delivery_option')->first()}} <br />
                                Pay method: {{App\Models\OrderItem::where(['order_id'=>$orders->order_id])->pluck('payment_getway')->first()}} <br />
                                Status: {{App\Models\OrderItem::where(['order_id'=>$orders->order_id])->pluck('status')->first()}}
                            </p>
                            <!-- <a href="#">Download info</a> -->
                        </div>
                    </article>
                </div>
                <!-- col// -->
                <div class="col-md-4">
                    <article class="icontext align-items-start">
                        <span class="icon icon-sm rounded-circle bg-primary-light">
                            <i class="text-primary material-icons md-place"></i>
                        </span>
                        <div class="text">
                            <h6 class="mb-1">Deliver to</h6>
                            <p class="mb-1">
                                
                            {{App\Models\Order::where('id',$orders->order_id)->pluck('address')->first()}}
                            </p>
                            <!-- <a href="#">View profile</a> -->
                        </div>
                    </article>
                </div>
                <!-- col// -->
            </div>
            <!-- row // -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th >Product</th>
                                    <!-- <th width="20%">Unit Price</th>
                                    <th width="20%">Quantity</th> -->
                                    <th  class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                <tr>
                                    <td>
                                        <a class="itemside" href="#">
                                            <div class="left">
                                               @php
                                                  $pro = App\Models\Product::where('id',$orders->product_id)->first();
                                                 $img = $pro->getImage($pro->thumbnail);
                                                @endphp
                                               
                                                <img src="{{ asset('uploads/files/'.$img) }}" width="40" height="40" class="img-xs"
                                                    alt="Item" />
                                            </div>
                                            <div class="info">{{App\Models\Product::where(['id'=>$orders->product_id])->pluck('name')->first()}}</div>
                                        </a>
                                    </td>
                                    <td>AED {{ $orders->product_price}}</td>
                                    <td></td>
                                    <td class="text-end"></td>
                                </tr>
                              
                                <tr>
                                    <!-- <td colspan="4">
                                        <article class="float-end">
                                            <dl class="dlist">
                                            <dt>Subtotal:</dt>
                                            @php
                                             $proplusprice =  App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('products_quantityprice')->sum();
                                             @endphp
                                              <dd>{{round($proplusprice,2)}}</dd>
                                            </dl>
                                            <dl class="dlist">
                                                <dt>Coupon Discount:</dt>
                                                <dd>{{round($orders->coupondiscount,2)}}</dd>
                                            </dl>
                                          
                                            <dl class="dlist">
                                                <dt>Shipping Charges:</dt>
                                                <dd>{{round($orders->deliverycharges,2)}}</dd>
                                            </dl>
                                           
                                            <dl class="dlist">
                                                <dt>VAT:</dt>
                                                <dd>{{round($orders->total_vat,2)}}</dd>
                                            </dl>
            
                                          

                                            <dl class="dlist">
                                                <dt>Grand total:</dt>
                                                @if($orders->coupon_payment != NULL)
                                                <dd><b class="h5">{{round($orders->coupon_payment,2)}}</b></dd>
                                                @else
                                                <dd><b class="h5">{{ round(App\Models\OrderItem::where(['order_id'=>$orders->order_id])->pluck('product_price')->sum(),2) }}</b></dd>
                                                @endif
                                            </dl>
                                            <dl class="dlist">
                                                <?php $order = App\Models\OrderItem::where(['order_id'=>$orders->order_id])->first()?>
                                                <dt class="text-muted">Status:</dt>
                                          
                                                
                                            </dl>
                                        </article>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- table-responsive// -->
                </div>

            </div>
        </div>
      
    </div>
 
</section>
@endif
@endsection
@section('scripts')
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js">

</script>
<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});
</script>
@endsection