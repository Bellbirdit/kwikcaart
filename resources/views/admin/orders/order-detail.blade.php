@extends('layout/master')
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
                <div class="col-lg-4 col-md-4 mb-lg-0 mb-15">
                    <span> <i class="material-icons md-calendar_today"></i> <b>{{ \Carbon\Carbon::parse($orders->created_at)->isoFormat('MMM Do YYYY')}} </b>
                    </span> <br />
                    <span class="text-muted">Order ID: {{$orders->order_number}}</span>
                </div>
                <div class="col-lg-4 col-md-4 mb-lg-0 mb-15 text-md-center">
                    @if($orders->delivery_option == 'express_delivery')
                        <span> <i class="material-icons"></i> <b>Express Delivery<span>
                     @elseif($orders->delivery_option == 'self_pickup')
                        <span> <i class="material-icons"></i> <b>Self Pickup </b>
                    </span> <br />
                    <span class="text-muted">Date: {{$orders->pick_date}}</span><br />
                    <span class="text-muted">Time: {{$orders->pick_time}}</span>
                    @elseif($orders->delivery_option == 'standerd_delivery')
                    <span> <i class="material-icons"></i> <b>Standard Delivery </b>
                    </span> <br />
                    <span class="text-muted">Date: {{$orders->pick_date}}</span><br />
                    <span class="text-muted">Time: {{$orders->pick_time}}</span>
                    @endif
                </div>
                <div class=" col-lg-3 col-md-3 mb-lg-0 mb-15 ">
                    <a href="#" data-bs-toggle="dropdown"
                    class="btn btn-light rounded " aria-expanded="false">Update Order </a>
                    <div class="dropdown-menu" style="margin: 0px;">
                    @if($orders->order_status == 'pending')
                        <a href="javascript:;" id="{{$orders->id}}" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel/Reject</a>
                        <a class="dropdown-item change_status change_st{{$orders->id}}"  id="{{$orders->id}}" href="javascript:;">Accepted</a>
                    @endif
                    @if($orders->order_status == 'accepted')
                        <a class="dropdown-item change_status change_st{{$orders->id}}"  id="{{$orders->id}}" href="javascript:;">Dispatch</a>
                         <a href="javascript:;" id="{{$orders->id}}" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel/Reject</a>
                    @endif
                    @if($orders->order_status == 'dispatch')
                        <a class="dropdown-item change_status change_st{{$orders->id}}"  id="{{$orders->id}}" href="javascript:;">Deliver</a>
                         <a href="javascript:;" id="{{$orders->id}}" class="dropdown-item text-danger btnCancel" data-bs-target="#orderCancellation" data-bs-toggle="modal">Cancel/Reject</a>
                    @endif
                    </div>

                </div>
                <div class="col-lg-1 col-md-1 mb-lg-0 mb-15 text-md-end">
                    <a href="/order/download/{{$orders->id}}" class="btn btn-primary"><i class="fa fa-file-pdf"></i></a>
                </div>
                <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Created <br>
                                                    <span class="text-muted">{{date("d F,Y h:i A", strtotime($orders->created_at))}}</span>
                                                </td>
                                                @php
                                                $statusOrder = [];
                                                @endphp
                                                
                                                @if(!$orders->orderActivity->isEmpty())
                                                    @foreach($orders->orderActivity as $activity)
                                                    @php
                                                        if(!in_array($activity->status, $statusOrder)){
                                                            $statusOrder[] = $activity->status;
                                                        }else{
                                                            continue;
                                                        }
                                                    @endphp
                                                    <td>
                                                        {{ucfirst($activity->status)}} <br>
                                                        <span class="text-muted">{{date("d F,Y h:i A", strtotime($activity->created_at))}}</span>
                                                    </td>
                                                    @endforeach
                                                @else
                                                    <td>
                                                        {{ucfirst($orders->order_status)}} <br>
                                                        <span class="text-muted">{{date("d F,Y h:i A", strtotime($orders->updated_at))}}</span>
                                                    </td>
                                                @endif
                                                
                                                
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
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
                                {{$orders->first_name}} {{$orders->last_name}} <br />
                                {{$orders->email}} <br />
                                {{$orders->phone}}
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
                                Shipping: {{$orders->delivery_option}} <br />
                                Pay method: {{App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('payment_getway')->first()}} <br />
                                Status: {{$orders->order_status}}
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
                                               @php
                                                  $pro = App\Models\Product::where('id',$ord->product_id)->first();
                                                 $img = $pro->getImage($pro->thumbnail);
                                                @endphp
                                               
                                                <img src="{{ asset('uploads/files/'.$img) }}" width="40" height="40" class="img-xs"
                                                    alt="Item" />
                                            </div>
                                            <div class="info">{{App\Models\Product::where(['id'=>$ord->product_id])->pluck('name')->first()}}</div>
                                        </a>
                                    </td>
                                    <td>AED {{round($ord->product_price,2) }}</td>
                                    <td>{{$ord->quantity}}</td>
                                    <td class="text-end">AED {{round($ord->products_quantityprice,2) }}</td>
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
                                                <dt>Grand total:</dt>
                                                @if($orders->coupon_payment != NULL)
                                                <dd><b class="h5">{{round($orders->coupon_payment,2)}}</b></dd>
                                                @else
                                                <dd><b class="h5">{{ round(App\Models\OrderItem::where(['order_id'=>$orders->id])->pluck('product_price')->sum(),2) }}</b></dd>
                                                @endif
                                            </dl>
                                            <dl class="dlist">
                                                <?php $order = App\Models\OrderItem::where(['order_id'=>$orders->id])->first();
                                                $order = $orders;
                                                $order->status = $order->order_status;
                                                ?>
                                                <dt class="text-muted">Status:</dt>
                                                @if(isset($order))
                                                @if($order->status == 'pending')
                                                    <span class="badge rounded-pill alert-success text-success">Pending
                                                        </span>
                                                     @elseif($order->status=='accepted')
                                                <span class="badge rounded-pill alert-success text-success">Order Accepted
                                                        </span>
                                                    @elseif($order->status=='dispatch')
                                                <span class="badge rounded-pill alert-success text-success">On the way
                                                        </span>
                                                @elseif($order->status == 'deliverd')
                                                <span class="badge rounded-pill alert-success text-success">Deliverd
                                                        </span>
                                                        @elseif($order->status=='refund rejected')
                                                <span class="badge rounded-pill alert-danger">Return Rejected</span>
                                                @elseif($order->status=='cancelled')
                                                <span class="badge rounded-pill alert-danger">Cancelled</span>
                                                @elseif($order->status=='Return Pending')
                                                <span class="badge rounded-pill alert-danger">Return Requested</span>
                                                @elseif($order->status=='refunded')
                                                <span class="badge rounded-pill alert-success">Refunded</span>
                                                
                                                @endif
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

            </div>
        </div>
      
    </div>
    <div class="modal fade" id="orderCancellation">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <form id="cancel_order" class="cancel_order">
                <div class="modal-header">
                    <h6 class="modal-title" style="color:#b12525;">Order Cancellation/Rejection</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="order_id">
                    <input type="hidden" name="status" value="cancel">
                    <p style="color: #79515 !important;">Please provide breif details for Cancellation/Rejection the order</p>
                    <textarea class="form-control" name="reason" rows="4" placeholder="wrtie here..."></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="submit" id="btnSubmit">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i> Submit
                    </button>
                </div>
            </form>
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
<script>

      $(document).on('submit', '#cancel_order', function(e) {
            var id = $("#order_id").val();
            e.preventDefault();
            $.ajax({
                url: '/api/cancel/order/'+id,
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-pulse").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-pulse").css('display', 'none');
                },
                success: function(response) {
                    // console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $(".cancel_order")[0].reset();
                        location.reload();

                    }
                },
                error: function(error) {
                    // console.log(error);
                }
            });
        })
    $(document).on('click', '.change_status', function () {
        var order_id = $(this).attr('id');
        console.log(order_id);
        var st = $(this).html();
        if (st == 'Accepted') {
            var status = 'accepted';
        }else if (st == 'Dispatch') {
            var status = 'dispatch';
        } else {
            status = "deliverd";
        }

        $.ajax({
            url: "/order/status",
            type: "get",
            data: {
                order_id: order_id,
                status: status
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])

                    location.reload();
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    });
    $(document).on('click', '.btnCancel', function () {

    var id = $(this).attr('id');
    $("#order_id").val(id);
    })
</script>
@endsection