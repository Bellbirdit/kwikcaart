@extends('layout/master')
@section('title')
Kwikcaart | Dashboard
@endsection
@section('content')

<section class="content-main">

    @if(Auth::user()->type =='1')
    <div class="content-header">
        <div>
            <h2 class="content-title card-title"> Admin Dashboard</h2>
            <p>{{ $admin->address }}</p>
        </div>
        <div>
            <h4>
                {{$admin->name}}
            </h4>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Customer</h6>
                        <?php $admincustomers = App\Models\User::whereHas('roles', function ($q) {
                            $q->where('name', 'User');})->count();
                        ?>
                        <span>{{$admincustomers}}</span>

                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Order</h6>

                        <?php
                            $adminorders = App\Models\Order::all()->count();
                            ?>

                        <span>{{$adminorders}}</span>
                        <!-- <span class="text-sm"> Excluding orders in transit </span> -->
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Products</h6>
                        <?php $adminproducts = App\Models\Product::where('stock', 'yes')->where('published', 1)->get();
$adminproducts = $adminproducts->unique('barcode')->count();
$admincategories = App\Models\Category::all()->count();?>
                        <span>{{$adminproducts}}</span>
                        <span class="text-sm"> In {{$admincategories}} Categories </span>
                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Cancelled Order</h6>
                        <?php $admiincancelled = App\Models\Order::where('order_status', 'cancelled')->count();?>
                        <span>{{$admiincancelled}}</span>
                       
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <h4 class="card-title">Latest orders</h4>

        </header>
        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle" scope="col">Date</th>
                                <th class="align-middle" scope="col">Order No</th>
                                <th class="align-middle" scope="col">Customer Name</th>
                                <th class="align-middle" scope="col">Amount</th>
                                <th class="align-middle" scope="col">Payment Method</th>
                                <th class="align-middle" scope="col">Payment Status</th>
                                <th class="align-middle" scope="col">Order Status</th>
                                <!-- <th class="align-middle" scope="col">Shipping Method</th> -->
                                <th class="align-middle" scope="col">Store Name</th>
                                <th class="align-middle" scope="col">View Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($adminordersss) && sizeof($adminordersss)>0)
                            @foreach($adminordersss as $adorder)
                            <tr>
                            <td>{{ \Carbon\Carbon::parse($adorder->created_at)->isoFormat('MMM Do YYYY')}}</td>
                                <td>{{$adorder->order_number}}</td>
                                <td>{{$adorder->first_name}}<br>
                                <span>{{$adorder->phone}}</span>
                                </td>
                                    @if($adorder->coupon_payment != NULL)
                                    <td>{{round($adorder->coupon_payment,2)}}</td>
                                    @else
                                    <td>{{ round(App\Models\OrderItem::where(['order_id'=>$adorder->id])->pluck('product_price')->sum(),2)}}
                                    </td>
                                    @endif
                                @php
                                    $adminorderi = App\Models\OrderItem::where('order_id',$adorder->id)->first();
                                @endphp
                                @if(isset($adminorderi))

                                <td>{{$adminorderi->payment_getway}}</td>
                                @else
                                <td><span class="badge rounded-pill alert-danger">n/a </span></td>
                                
                                @endif
                                @if(isset($adminorderi))
                                        @if($adorder->order_status == 'deliverd' && $adminorderi->payment_getway == 'Cash on Delivery')
                                        <td>
                                            <span class="badge rounded-pill alert-success">Paid
                                            </span>
                                        </td>
                                        @elseif($adorder->order_status !== 'deliverd' && $adminorderi->payment_getway == 'Cash on Delivery')
                                        <td>
                                            <span class="badge rounded-pill alert-warning">Pending

                                            </span>
                                        </td>
                                        @elseif($adminorderi->payment_getway == 'stripe')
                                        <td>
                                            <span class="badge rounded-pill alert-success">Paid
                                            </span>
                                        </td>
                                        @endif
                                @else
                                    <td>
                                        <span class="badge rounded-pill alert-danger">n/a </span>
                                    </td>
                               
                                @endif
                                
                                <?php
                                    $status = App\Models\OrderItem::where(['order_id' => $adorder->id])->pluck('status')->first();
                                    $id = App\Models\OrderItem::where(['order_id' => $adorder->id])->pluck('id')->first();
                                    ?>

                                <td>
                                    
                                    @if($status == 'pending')
                                    <span class="badge rounded-pill alert-warning status{{$adorder->id}}">Pending</span>

                                    @elseif($status=='accepted')
                                    <span class="badge rounded-pill alert-success status{{$adorder->id}}">Accepted</span>
                                    @elseif($status=='dispatch')
                                    <span class="badge rounded-pill alert-success status{{$adorder->id}}">Dispatched</span>
                                    @elseif($status == 'deliverd')
                                    <span class="badge rounded-pill alert-success status{{$adorder->id}}">Deliverd</span>
                                    @elseif($status=='cancelled')
                                        <span class="badge rounded-pill alert-danger">Cancelled</span>
                                        @elseif($status=='refund rejected')
                                    <span class="badge rounded-pill alert-danger status{{$adorder->id}}">Return Rejected</span>

                                     @elseif($status=='Return Pending')
                                    <span class="badge rounded-pill alert-danger status{{$adorder->id}}">Return Requested</span>
                                    @elseif($status=='refunded')
                                    <span class="badge rounded-pill alert-success status{{$adorder->id}}">Refunded</span>
                                    @else
                                   <span class="badge rounded-pill alert-danger">n/a </span>

                                    @endif
                                </td>
                               <!-- <td>{{$adorder->delivery_option}}</td> -->
                                <td>
                                <?php
                                        $adstorename = App\Models\User::where(['code' => $adorder->store_id])->pluck('name')->first();

                                        ?>
                                <span> <i class="material-icons"></i> <b> {{$adstorename}}<span><br>
                                    <span> <i class="material-icons"></i> <b>Store Code: {{$adorder->store_id}}<span>

                                </td>
                                <td>
                                    <a href="order/detail/{{ $adorder->id }}" class="btn btn-xs"> View details</a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- table-responsive end// -->
        </div>
    </div>

    @elseif(Auth::user()->type =='2')
    <div class="content-header">
        <div>
            <h2 class="content-title card-title"> Store Dashboard</h2>
            <p>{{ $storeAddress->address }}</p>
        </div>
        <div>
            <h4>
                {{ $storeAddress->name }}
            </h4>
            @if($storeAddress != NULL)
                <h6>
                    Store Code: {{ $storeAddress->code }}
                </h6>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Customer</h6>

                        <span>{{$storecustomercount}}</span>

                    </div>
                </article>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title">Total Order</h6>
                        <span>{{$storetotalorders}}</span>

                    </div>
                </article>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-body mb-4">
                <article class="icontext">
                    <div class="text">
                        <h6 class="mb-1 card-title ">Cancelled Order</h6>
                        <span >{{$storecancelled}}</span>
                       
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <header class="card-header">
            <h4 class="card-title">Latest orders</h4>

        </header>
        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-light">
                            <tr>

                                <th class="align-middle" scope="col">Date</th>
                                <th class="align-middle" scope="col">Order No</th>
                                <th class="align-middle" scope="col">Customer Name</th>
                                <th class="align-middle" scope="col">Amount</th>
                                <th class="align-middle" scope="col">Payment Method</th>
                                <th class="align-middle" scope="col">Payment Status</th>
                                <th class="align-middle" scope="col">Order Status</th>
                                <th class="align-middle" scope="col">Shipping Method</th>
                                <th class="align-middle" scope="col">View Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($ordersss) && sizeof($ordersss)>0)
                        @foreach($ordersss as $order)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('MMM Do YYYY')}}</td>

                                <td><a href="/store/order/detail/{{ $order->id }}" class="fw-bold">{{$order->order_number}}</a></td>
                                <td>{{$order->first_name}}<br>
                                <span>{{$order->phone}}</span>
                                </td>
                                @if($order->coupon_payment != NULL)
                                <td>{{round($order->coupon_payment,2)}}</td>
                                @else
                                <td>{{ round(App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('product_price')->sum(),2)}}
                                </td>
                                @endif
                               
                                @php
                                    $adminorderi = App\Models\OrderItem::where('order_id',$order->id)->first();
                                @endphp
                                @if(isset($adminorderi))
                                <td>{{$adminorderi->payment_getway}}</td>
                                @else
                                <td><span class="badge rounded-pill alert-danger">n/a</span></td>
                                @endif
                                @if(isset($adminorderi))
                                    @if($order->order_status == 'deliverd' && $adminorderi->payment_getway == 'Cash on Delivery')
                                    <td>
                                        <span class="badge rounded-pill alert-success">Paid
                                        </span>
                                    </td>
                                    @elseif($order->order_status !== 'deliverd' && $adminorderi->payment_getway == 'Cash on Delivery')
                                    <td>
                                        <span class="badge rounded-pill alert-warning">Pending</span>
                                    </td>
                                    @elseif($adminorderi->payment_getway == 'stripe')
                                    <td>
                                        <span class="badge rounded-pill alert-success">Paid
                                        </span>
                                    </td>
                                    @endif
                                @else
                                <td><span class="badge rounded-pill alert-danger">n/a</span></td>
                                @endif
                               
                                <?php
                                    $status = App\Models\OrderItem::where(['order_id' => $order->id])->pluck('status')->first();
                                    $id = App\Models\OrderItem::where(['order_id' => $order->id])->pluck('id')->first();
                                    ?>

                                <td>
                                @if($status == 'pending')
                                    <span class="badge rounded-pill alert-warning status{{$order->id}}">Pending</span>

                                    @elseif($status=='accepted')
                                    <span class="badge rounded-pill alert-success status{{$order->id}}">Accepted</span>
                                    @elseif($status=='dispatch')
                                    <span class="badge rounded-pill alert-success status{{$order->id}}">Dispatched</span>
                                    @elseif($status == 'deliverd')
                                    <span class="badge rounded-pill alert-success status{{$order->id}}">Deliverd</span>
                                    @elseif($status=='cancelled')
                                        <span class="badge rounded-pill alert-danger">Cancelled</span>
                                        @elseif($status=='refund rejected')
                                    <span class="badge rounded-pill alert-danger status{{$order->id}}">Return Rejected</span>

                                     @elseif($status=='Return Pending')
                                    <span class="badge rounded-pill alert-danger status{{$order->id}}">Return Requested</span>
                                    @elseif($status=='refunded')
                                    <span class="badge rounded-pill alert-success status{{$order->id}}">Refunded</span>
                                   @else 
                                   <span class="badge rounded-pill alert-danger">n/a</span>
                                    @endif
                                </td>

                                <td>
                                @if($order->delivery_option == 'express_delivery')
                                    <span> <i class="material-icons"></i> <b>Express Delivery<span>
                                @elseif($order->delivery_option == 'self_pickup')
                                    <span> <i class="material-icons"></i> <b>Self Pickup </b>
                                </span> <br />

                                @elseif($order->delivery_option == 'standerd_delivery')
                                <span> <i class="material-icons"></i> <b>Standard Delivery </b>
                                </span> <br />

                                @endif
                                </td>
                                <td>
                                    <a href="/store/order/detail/{{ $order->id }}" class="btn btn-xs"> View details</a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @endif
</section>


<script
     src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js">
</script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
@endsection
