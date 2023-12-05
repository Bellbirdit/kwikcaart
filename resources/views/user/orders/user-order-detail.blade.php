@extends('user/layout/master')
@section('title')
Kwikcaart | Order Detail
@endsection
@section('content')
@if(isset($orders) && !empty($orders))
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Order detail</h2>
            <p>Details for Order ID: {{$orders->order_number}}</p>
        </div>
    </div>
    <div class="card">
        <header class="card-header">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                    <span> <i class="material-icons md-calendar_today"></i> <b>{{ \Carbon\Carbon::parse($orders->created_at)->isoFormat('MMM Do YYYY')}} </b>
                    </span> <br />
                    <small class="text-muted">Order ID: {{$orders->order_number}}</small>
                </div>
                
                <div class="col-lg-6 col-md-6 mb-lg-0 mb-15 text-md-end">
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
                <!-- <div class="col-lg-6 col-md-6 ms-auto text-md-end">
                    <select class="form-select d-inline-block mb-lg-0 mr-5 mw-200">
                        <option>{{$orders->status}}</option>
                    </select>
                    <a class="btn btn-primary" href="#">Save</a>
                    <a class="btn btn-secondary print ms-2" href="#"><i class="icon material-icons md-print"></i></a>
                </div> -->
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
                                {{$orders->first_name}} {{$orders->last_name}} <br />
                                {{$orders->email}} <br />
                                {{$orders->phone}}
                            </p>
                            <a href="#">View profile</a>
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
                                Shipping: {{$orders->delivery_option}} <br />
                                Pay method: {{App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('payment_getway')->first()}} <br />
                                Status: {{App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('status')->first()}}
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
                                
                                {!! nl2br($orders->address) !!}
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
                                    <th width="40%">Product</th>
                                    <th width="20%">Unit Price</th>
                                    <th width="20%">Quantity</th>
                                    <th width="20%" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order as $ord)
                                
                                <tr>
                                    <td>
                                        <a class="itemside" href="#">
                                            <div class="left">
                                                 <?php 

                                                  $pro = App\Models\Product::where('id',$ord->product_id)->first();
                                                 
                                                  
                                                 $img = $pro->getImage($pro->thumbnail);
                                                 
                                                 ?>
                                               
                                                <img src="{{ asset('uploads/files/'.$img) }}" width="40" height="40" class="img-xs"
                                                    alt="Item" />
                                            </div>
                                            <div class="info">{{App\Models\Product::where(['id'=>$ord->product_id])->pluck('name')->first()}}</div>
                                        </a>
                                    </td>
                                    <td>AED {{App\Models\Product::where(['id'=>$ord->product_id])->pluck('discounted_price')->first()}}</td>
                                    <td>{{$ord->quantity}}</td>
                                    <td class="text-end">AED {{$ord->products_quantityprice}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4">
                                        <article class="float-end">
                                            <dl class="dlist">
                                                <dt>Subtotal:</dt>
                                                @php
                                                 $proplusprice =  App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('products_quantityprice')->sum();
                                               @endphp
                                                <dd>{{$proplusprice}}</dd>
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
                                                <dd><b class="h5">{{$orders->coupon_payment}}</b></dd>
                                                @else
                                                <dd><b class="h5">{{App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('product_price')->sum()}}</b></dd>
                                                @endif
                                            </dl>
                                            <dl class="dlist">
                                                <?php $order = App\Models\OrderItem::where(['order_id'=>$orders->id])->first()?>
                                                <dt class="text-muted">Status:</dt>
                                                @if($order->status == 'pending')
                                                    <span class="badge rounded-pill alert-danger text-danger">Processing
                                                        </span>
                                                    @elseif($order->status=='dispatch')
                                                <span class="badge rounded-pill alert-success text-success">On the way
                                                        </span>
                                                @elseif($order->status == 'deliverd')
                                                <span class="badge rounded-pill alert-success text-success">Deliverd
                                                        </span>

                                                @endif
                                                
                                            </dl>
                                        </article>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- table-responsive// -->
                </div>
                <!-- col// -->
             
                <!-- col// -->
            </div>
        </div>
        <!-- card-body end// -->
    </div>
    <!-- card end// -->
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