
<section class="content-main" id="order">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Order List</h2>
            <!-- <p>Lorem ipsum dolor sit amet.</p> -->
        </div>
        <div>
            <input type="text" placeholder="Search order ID" class="form-control bg-white">
        </div>
    </div>
    <div class="card1 mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <input type="text" placeholder="Search..." class="form-control">
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <select class="form-select">
                        <option>Status</option>
                        <option>Active</option>
                        <option>Disabled</option>
                        <option>Show all</option>
                    </select>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <select class="form-select">
                        <option>Show 20</option>
                        <option>Show 30</option>
                        <option>Show 40</option>
                    </select>
                </div>
            </div>
        </header>
        <!-- card-header end// -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th scope="col">Name</th>
                       
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php $orders = App\Models\Order::where('user_id',Auth::user()->id)->get(); ?>

                        @foreach($orders as $order)

                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td><b>{{ $order->first_name }}</b> <b>{{ $order->last_name }}</b></td>
                               
                                <td>{{ $order->phone }}</td>
                                <td>{{ $order->email }}</td>
                                @if($order->coupon_payment != NULL)
                                <td>{{$order->coupon_payment}}</td>
                                @else
                                <td>{{ App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('product_price')->sum()}}
                                </td>
                                @endif
                                <?php $status = App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('status')->first();
                                
                                $refund = App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('refund_status')->first(); 
                                $payment_getway = App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('payment_getway')->first(); 

                                
                                $check = App\Models\CancelRequest::where('order_id',$order->id)->first();
                              ?>
                                <td>

                                 @if($refund == 2)
                                 <span class="badge rounded-pill alert-success">Refunded</span>
                                 @elseif($refund == 1)
                                 <span class="badge rounded-pill alert-info">Request forwarded</span>

                                    @else
                                        @if($status == 'pending')
                                        <span class="badge rounded-pill alert-warning">Processing</span>
                                        @elseif($status=='cancelled')
                                        <span class="badge rounded-pill alert-success">Cancelled</span>
                                        @elseif($status=='dispatch')
                                        <span class="badge rounded-pill alert-success">Dispatched</span>
                                        @elseif($status == 'deliverd')
                                        <span class="badge rounded-pill alert-success">Deliverd</span>
                                    @endif
                                @endif

                                </td>
                                
                                <td>{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('MMM Do YYYY')}}</td>
                                <td class="text-end">
                                    
                                    <div class="dropdown1">
                                        <a href="#" data-bs-toggle="dropdown"
                                            class="btn btn-light rounded btn-sm font-sm" aria-expanded="false"> ... </a>
                                        <div class="dropdown-menu" style="margin: 0px;">
                                            <a class="dropdown-item" href="/user/order/detail/{{$order->id}}" >View detail</a>
                                            @if($refund == 2)

                                            @else
                                             
                                            @if($status == 'pending' && $payment_getway  == 'Cash on Delivery')

                                            <a class="dropdown-item cancel_order" href="javascript:;"
                                                id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                               >Cancel Order </a>
                                            @elseif($status == 'deliverd' && $payment_getway  == 'Cash on Delivery')
                                           
                                            <a class="dropdown-item select_pro" data-bs-toggle="modal" data-bs-target="#exampleModal" href="javascript:;"
                                                id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                               >Return Order
                                                Request</a>

                                                @elseif($payment_getway  == 'stripe')
                                           
                                                    <a class="dropdown-item select_pro" data-bs-toggle="modal" data-bs-target="#exampleModal" href="javascript:;"
                                                        id="{{ $order->id }}" store_id="{{ $order->store_id }}"
                                                        >Return Order
                                                        Request</a>




                                                @endif   
                                           
                                            
                                            
                                             @endif   


                                            <!-- <a class="dropdown-item text-danger" href="#">Delete</a> -->
                                        </div>
                                        <!-- Button trigger modal -->


                                    </div>
                                    <!-- dropdown //end -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->




    </div>
    <!-- card end// -->
<!-- pagination -->
</section>
<!-- Modal -->
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

                        <select class="form-select append_option" aria-label="Default select example " 
                            name="product_id">

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


         $(document).on('click', '.cancel_order', function(e) {
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
                            url: '/cancel/order/' + id,
                            type: "get",
                            dataType: "JSON",
                            success: function(response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to cancel order.", "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Cancelled!", "Order has been Cancelled.",
                                        "success");
                                    location.reload()
                                }
                            },
                            error: function(error) {
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
