<div class="tab-pane fade active show" id="dashboard" role="tabpanel" aria-labelledby="orders-tab">
    <div class="card">
        @php
         $orders = App\Models\Order::orderBy('id', 'DESC')->where('user_id',Auth::user()->id)->get();
         @endphp
        @foreach($orders as $order)
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <div class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$order->id}}"
                        aria-expanded="true" aria-controls="collapseOne">
                        <header class="card-header p-0">
                            <div class="row align-items-center">
                                <div class="col-lg-12 col-md-12 mb-lg-0 ">
                                    <span> <i class="material-icons md-calendar_today"></i>
                                        <b>{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('MMM Do YYYY')}}</b> </span> <br />
                                    <span>Order ID:{{$order->order_number}} </span>


                                </div>
                            </div>
                        </header>
                    </div>
                </h2>

                <div id="collapseOne{{$order->id}}" class="accordion-collapse collapse " aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="card-body border">
                        <!-- row // -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td>
                                                    Created <br>
                                                    <small>{{date("d F,Y h:i A", strtotime($order->created_at))}}</small>
                                                </td>
                                                @php
                                                $statusOrder = [];
                                                @endphp
                                                
                                                @if(!$order->orderActivity->isEmpty())
                                                    @foreach($order->orderActivity as $activity)
                                                    @php
                                                        if(!in_array($activity->status, $statusOrder)){
                                                            $statusOrder[] = $activity->status;
                                                        }else{
                                                            continue;
                                                        }
                                                    @endphp
                                                    <td>
                                                        {{ucfirst($activity->status)}} <br>
                                                        <small>{{date("d F,Y h:i A", strtotime($activity->created_at))}}</small>
                                                    </td>
                                                    @endforeach
                                                @else
                                                    <td>
                                                        {{ucfirst($order->order_status)}} <br>
                                                        <small>{{date("d F,Y h:i A", strtotime($order->updated_at))}}</small>
                                                    </td>
                                                @endif
                                                
                                                
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="40%">Product</th>
                                                <th width="20%">Unit Price</th>
                                                <th width="20%">Quantity</th>
                                                <th width="20%" class="text-end">Total
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                        $orderdetails = App\Models\OrderItem::where('order_id',$order->id)->get();
                                        @endphp

                                        @foreach($orderdetails as $detail)
                                        @php
                                            $pro = App\Models\Product::where('id',$detail->product_id)->first();
                                            if(!$pro) continue;
                                            $img = $pro->getImage($pro->thumbnail);
                                        @endphp
                                            <tr>
                                                <td>
                                                    <a class="itemside" href="#">
                                                        <div class="left">
                                                            <img src="{{ asset('uploads/files/'.$img) }}" width="40"
                                                                height="40" class="img-xs" alt="Item" />
                                                        </div>
                                                        <div class="info">{{$pro->name}} <br> 
                                                        @if($detail->quantity == 0)
                                                        <small style="color:red;">Item Not Available</small>
                                                        @endif
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>{{$detail->product_price}}</td>
                                                <td>{{$detail->quantity}}</td>
                                                <td class="text-end">{{round($detail->products_quantityprice,2)}}</td>
                                            </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="4">
                                                    <article class="float-end">
                                                        <dl class="dlist">
                                                        <dt>Subtotal:</dt>
                                                            @php
                                                            $proplusprice =  App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('products_quantityprice')->sum();
                                                             @endphp
                                                            <dd>{{round($proplusprice,2)}}</dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt>Shipping cost:</dt>
                                                            @if($order->deliverycharges !=NULL)
                                                            <dd>{{round($order->deliverycharges,2)}}</dd>
                                                            @else
                                                            <dd>
                                                                0
                                                            </dd>
                                                            @endif
                                                        </dl>
                                                        
                                                        <dl class="dlist">
                                                            <dt>Coupon Discount:</dt>

                                                            @if($order->coupondiscount !=NULL)
                                                            <dd>{{$order->coupondiscount}}</dd>
                                                            @else
                                                            <dd>
                                                                0
                                                            </dd>
                                                            @endif
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt>Grand total:</dt>
                                                            <dd><b class="h5">{{round($order->coupon_payment,2)}}</b>
                                                            </dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt class="text-muted">
                                                                Status:</dt>
                                                            <dd>

                                                                    @if($order->order_status == 'pending')
                                                                        <span
                                                                            class="badge rounded-pill alert-danger ">Pending</span>
                                                                             @elseif($order->order_status=='accepted')
                                                                        <span
                                                                            class="badge rounded-pill alert-success ">Order Accepted</span>
                                                                    @elseif($order->order_status=='dispatch')
                                                                        <span
                                                                            class="badge rounded-pill alert-success ">On The Way</span>

                                                                    @elseif($order->order_status=='cancelled')
                                                                        <span class="badge rounded-pill alert-danger">Cancelled</span>
                                                                         @elseif($order->order_status=='Return Pending')
                                                                        <span class="badge rounded-pill alert-danger">Return Requested</span>
                                                                    @elseif($order->order_status == 'deliverd')
                                                                        <span
                                                                            class="badge rounded-pill alert-success">Deliverd</span>
                                                                            @elseif($order->order_status=='refund rejected')
                                                                        <span class="badge rounded-pill alert-danger">Return Rejected</span>
                                                                        @elseif($order->order_status=='refunded')
                                                                        <span class="badge rounded-pill alert-success">Refunded</span>
                                                                    @endif

                                                            </dd>
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

                            <div class="col-lg-3">

                                <div class="h-25 pt-4">
                                    <div class="text">
                                        <h6 class="mb-1">Order info</h6>
                                        <p class="mb-1">



                                            @if($order->delivery_option == 'express_delivery')
                                            Shipping: Express Delivery <br />

                                            @elseif($order->delivery_option == 'self_pickup')
                                            Shipping: Self Pickup <br />

                                            @elseif($order->delivery_option == 'standerd_delivery')
                                            Shipping: Standard Delivery<br />
                                            @else
                                            Shipping: Standard Delivery
                                            @endif



                                        </p>
                                        <!-- <a href="#">Download info</a> -->
                                    </div>
                                    <div class="text">
                                        <h6 class="mb-1">Deliver to</h6>
                                        <p class="mb-1">
                                            {{$order->address}}
                                        </p>
                                        <!-- <a href="#">View profile</a> -->
                                    </div>



                                    <div class="text">
                                        <h6 class="mt-5">

                                        <?php $status = App\Models\OrderItem::where(['order_id' => $order->id])->pluck('status')->first();
                                        
                                        $refund = App\Models\OrderItem::where(['order_id' => $order->id])->pluck('refund_status')->first();
                                        $payment_getway = App\Models\OrderItem::where(['order_id' => $order->id])->pluck('payment_getway')->first();
                                        
                                        $check = App\Models\CancelRequest::where('order_id', $order->id)->first();
                                        ?>
                                        @if($refund == 2)

                                            @else

                                            @if($status == 'pending' && $payment_getway  == 'Cash on Delivery')

                                            <a class="dropdown-item cancel_order" href="javascript:;"
                                                id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                               >Cancel Order </a>
                                            @elseif($status == 'deliverd' && $payment_getway  == 'Cash on Delivery' && $refund == '0')

                                            <a class="dropdown-item select_pro" data-bs-toggle="modal" data-bs-target="#exampleModal" href="javascript:;"
                                                id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                               >Return Order
                                                Request</a>

                                                @elseif($payment_getway  == 'stripe')

                                                    <a class="dropdown-item select_pro" data-bs-toggle="modal" data-bs-target="#exampleModal" href="javascript:;"
                                                        id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                                        >Return Order
                                                        Request</a>


                                                @elseif($status != 'cancelled')
                                                <a href="/user/dashboard?tab=support_ticket" class="text-danger">Generate ticket to cancel order</a>

                                                @endif



                                             @endif
                                                    </h6>
                                    </div>
                                </div>
                            </div>
                            <!-- col// -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Return Order  Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cancel_form">
                    <input type="hidden" name="order_id" class="order_id" value="">
                    <input type="hidden" name="store_id" class="store_id" value="">
                    <div class="append_select1">
                    </div>
                    <div class="append_select2">
                    </div>
                    <div class="mb-3 " id="append_option">
                        <label for="" class="form-label">Select product</label>

                        <select class="form-select append_option" multiple aria-label="Default select example "
                            name="product_id[]">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Select reason</label>
                        <select class="form-select" aria-label="Default select example" required name="reason">
                            <option disabled>Select one reason</option>
                            <option value="duplicate">Duplicate</option>
                            <option value="fraudulent">Fraudulent</option>
                            <option value="requested_by_customer">Requested by customer</option>
                            <option value="expired_uncaptured_charge">Expired uncaptured charge</option>
                        </select>
                    </div>
                    <!-- <div class="mb-3">
                    <label for="" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="" placeholder="name@example.com">
                </div> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary ">Done</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.btnCancel', function (e) {
        var id = $(this).attr('id');

        e.preventDefault();
        $.ajax({
            url: "/api/order/request/cancel",
            type: "post",
            data: {
                id: id
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            cache: false,
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                console.log(response)
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    btn.css('display', 'none');

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

</script>



<script>
    $(document).on('click', '.select_pro', function (e) {
        var id = $(this).attr('id');
        var store_id = $(this).attr('store_id');
        $('.store_id').val(store_id);
        $('.order_id').val(id);
        e.preventDefault();
        $.ajax({
            url: "/api/option/append/",
            type: "get",
            data: {
                id: id
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            cache: false,
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                console.log(response)
                if (response["status"] == "fail") {
                    $('#exampleModal').modal('hide')
                    toastr.error('Failed',response["msg"])

                } else if (response["status"] == "success") {
                    // toastr.success('Success',response["msg"])
                     if(response['html'] == "")
                     {
                        $('#append_option').css('display','none');
                     }else{
                        $('#append_option').css('display','block');

                        $('.append_option').html(response["html"])


                     }

                    $('.append_select1').html(response["select1"])
                    $('.append_select2').html(response["select2"])
                    // btn.css('display','none');

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#cancel_form").on('submit', (function (e) {
        console.log('ss')
        e.preventDefault();
        $.ajax({
            url: '/cancel/order/request',
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#btnSubmit").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function () {
                $("#btnSubmit").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])

                    $('#exampleModal').modal('hide')
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    }));


            $(document).on('click', '.cancel_order', function (e) {
        var id = $(this).attr('id')
        Swal.fire({
                title: "Are you sure?",
                text: "You will not able to recover this order",
                type: "warning",
                buttons: true,
                confirmButtonColor: "#ff5e5e",
                confirmButtonText: "Yes, Cancel it!",
                closeOnConfirm: false,
                dangerMode: true,
                showCancelButton: true
            })
            .then((deleteThis) => {
                if (deleteThis.isConfirmed) {
                    $.ajax({
                        url: '/api/cancel/order/' + id,
                        type: "get",
                        dataType: "JSON",
                        success: function (response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to cancel order.", "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Cancelled!", "Order has been Cancelled.",
                                    "success");
                                location.reload()
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        },
                        async: false
                    });
                } else {
                    Swal.close();
                }
            });
    });

</script>
@endsection
