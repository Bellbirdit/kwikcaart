@extends('layout/master')
@section('title')
Safeer | Customers
@endsection
@section('content')

<section class="content-main">

    <div class="card mb-4">

        <div class="card-body">
            <div class="row">

                <!--  col.// -->
                <div class="col-xl col-lg">
                    <h3>{{ $storecustomer->name }}</h3>
                    <p>{{ $storecustomer->address }}</p>
                </div>
               
            </div>
            <!-- card-body.// -->
            <hr class="my-4" />
            <div class="row g-4">
                <div class="col-md-12 col-lg-4 col-xl-2">
                    <article class="box">
                        <p class="mb-0 text-muted">Total orders:</p>
                        <h5 class="text-success">{{ $storcustome }}</h5>
                        <p class="mb-0 text-muted">Revenue:</p>
                        <h5 class="text-success mb-0">{{ round($storcustpayment,2) }}</h5>
                    </article>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <h6>Contacts</h6>
                    <p>
                        {{ $storecustomer->name }}<br />
                        {{ $storecustomer->email }} <br />
                        {{ $storecustomer->contact }}
                    </p>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <h6>Address</h6>
                    <p>
                        {{ $storecustomer->address }}
                    </p>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-xl-4 text-xl-end">
                    <map class="mapbox position-relative d-inline-block">
                        <img src="assets/imgs/misc/map.jpg" class="rounded2" height="120" alt="map" />
                        <span class="map-pin" style="top: 50px; left: 100px"></span>
                        <button
                            class="btn btn-sm btn-brand position-absolute bottom-0 end-0 mb-15 mr-15 font-xs">Large</button>
                    </map>
                </div>
                <!--  col.// -->
            </div>
            <!--  row.// -->
        </div>
        <!--  card-body.// -->
    </div>
    <section class="content-main">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Order detail</h2>

            </div>
        </div>
        @foreach($cusorder as $cusorders)
        <?php 
            $details = App\Models\OrderItem::where('order_id',$cusorders->id)->get(); 
            $proplusprice =  App\Models\OrderItem::where(['order_id'=>$cusorders->id])->pluck('products_quantityprice')->sum();
           
       ?>
        <div class="card">
            <header class="card-header">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                        <span> <i class="material-icons md-calendar_today"></i> <b>{{$cusorders->created_at}}</b> </span>
                        <br />
                        <small class="text-muted">Order ID: {{$cusorders->order_number}}</small>
                    </div>
                </div>
            </header>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-7">
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
                                    @foreach($details as $detail)
                                    
                                    @php
                                    $pro = App\Models\Product::where('id',$detail->product_id)->first();
                                                $img = $pro->getImage($pro->thumbnail);
                                                @endphp
                                    <tr>
                                        <td>
                                            <a class="itemside" href="#">
                                                <div class="left">
                                                    <img src="{{ asset('uploads/files/'.$img) }}" width="40" height="40"
                                                        class="img-xs" alt="Item" />
                                                </div>
                                                <div class="info">{{ App\Models\Product::where('id',$detail->product_id)->pluck('name')->first() }}</div>
                                            </a>
                                        </td>
                                        <td>{{ round($detail->product_price,2) }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td class="text-end">{{ round($detail->products_quantityprice,2) }}</td>
                                    </tr>

                                        @endforeach
                                    <tr>
                                        <td colspan="4">
                                            <article class="float-end">
                                            <dl class="dlist">
                                                    <dt>Subtotal:</dt>
                                                    <dd>{{ round($proplusprice,2) }}</dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Shipping cost:</dt>
                                                    <dd>{{$cusorders->deliverycharges}}</dd>
                                                </dl>
                                                  <dl class="dlist">
                                                    <dt>Coupon Discount:</dt>
                                                    <dd>{{$cusorders->coupondiscount}}</dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt>Grand total:</dt>
                                                    <dd><b class="h5">{{round($cusorders->coupon_payment,2)}}</b></dd>
                                                </dl>
                                                <dl class="dlist">
                                                    <dt class="text-muted">Status:</dt>
                                                    <dd>
                                                        <span class="">{{$cusorders->order_status}}</span>
                                                    </dd>
                                                </dl>
                                            </article>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-4">

                        <div class="h-25 pt-4">
                            <div class="text">
                                <h6 class="mb-1">Order info</h6>
                                <p class="mb-1">
                                    Shipping: {{ $cusorders->delivery_option }} <br />
                                    Pay method: {{App\Models\OrderItem::where('order_id',$cusorders->id)->pluck('payment_getway')->first();}} <br />
                                    Status:  {{$cusorders->order_status}}
                                </p>
                               
                            </div>
                            <div class="text">
                                <h6 class="mb-1">Deliver to</h6>
                                <p class="mb-1">
                                   {{$cusorders->address}}
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
</section>

@endsection
