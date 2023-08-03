@extends('layout/master')

@section('title')
Order cancel list
@endsection

@section('content')
<section class="content-main">
    <div class="content-header">
        
 
    </div>
    <div class="card mb-4">
        <!-- <header class="card-header">
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
        </header> -->
        <!-- card-header end// -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th scope="col"> Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><b>{{ App\Models\Order::where(['id'=>$order->order_id])->pluck('first_name')->first() }}</b>
                                </td>
                               
                                <td>{{ App\Models\Order::where(['id'=>$order->order_id])->pluck('phone')->first() }}
                                </td>
                                <td>{{ App\Models\Order::where(['id'=>$order->order_id])->pluck('email')->first() }}
                                
                                <td>{{$order->product_price}}
                                </td>


                                <!-- <?php $status = App\Models\cancelRequest::where(['order_id'=>$order->id])->pluck('status')->first();
                                
                              $id= App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('id')->first();
                               ?>    -->

                                <td>
                                    @if($order->status == 0)
                                        <span class="badge rounded-pill alert-warning status{{ $id }}">Cancel action
                                            required</span>
                                    @elseif($order->status == 1)
                                        <span class="badge rounded-pill alert-success status{{ $id }}">Request
                                            Approved</span>
                                    @elseif($order->status == 2)
                                        <span class="badge rounded-pill alert-danger status{{ $id }}">Rejected</span>
                                    @elseif($order->status == 3)
                                        <span class="badge rounded-pill alert-danger status{{ $id }}">Request
                                            cancel</span>

                                    @endif

                                </td>
                                <td>{{ $order->created_at }}</td>
                                <td class="text-end d-flex">
                                <div class="px-2">
                                    <a href="/return/detail/{{$order->id}}" class="btn btn-md rounded font-sm">Detail</a>
                                </div>
                                    @if($order->status == 0)
                               
                                        <div class="dropdown">
                                            <a href="#" data-bs-toggle="dropdown"
                                                class="btn btn-light rounded btn-sm font-sm" aria-expanded="false"> <i
                                                    class="material-icons md-more_horiz"></i> </a>
                                            <div class="dropdown-menu" style="margin: 0px;">
                                                <a class="dropdown-item btnApprove" dbid="1" href="javascript:;"
                                                    id="{{ $order->id }}">Approved</a>
                                                <!-- <a class="dropdown-item btnApprove" dbid="2" href="javascript:;"
                                                    id="{{ $order->id }}">Rejected</a> -->

                                                <a  class="dropdown-item btnCancel" dbid="2" href="javascript:;" 
                                                    id="{{ $order->id }}" data-bs-target="#orderCancellation" data-bs-toggle="modal">Reject</a>

                                            </div>
                                        </div>
                                    @endif

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
    <div class="modal fade" id="orderCancellation">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form id="cancel_order" class="cancel_order">
                    <div class="modal-header">
                        <h6 class="modal-title" style="color:#b12525;">Order Cancellation</h6><button aria-label="Close"
                            class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="order_id">
                        <input type="hidden" name="status" value="cancel">
                        <p style="color: #79515 !important;">Please provide breif details for cancelling the order
                        </p>
                        <textarea class="form-control" name="rejectreason" rows="4" placeholder="wrtie here..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary " type="submit" id="btnSubmit">
                            <i class="fas fa-spinner fa-spin" style="display:none;"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js">
</script>
<script>
     $(document).on('click', '.btnCancel', function () {
        var id = $(this).attr('id');
        $("#order_id").val(id);
    })

    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    $(document).on('click', '.change_status', function () {
        var id = $(this).attr('id');
        var st = $(this).html();
        if (st == 'Dispatch') {
            var status = 'dispatch';
        } else {
            status = "deliverd";
        }

        $.ajax({
            url: "/order/status",
            type: "get",
            data: {
                id: id,
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
                    $(".status" + id).html(response['current_status'])
                    $(".change_st" + id).html(response['order_status'])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    });

</script>
<script>

$(document).on('submit', '#cancel_order', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/api/order/request/reject/update',
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#btnSubmit").attr('disabled', true);
                $(".fa-pulse").css('display', 'inline-block');
            },
            complete: function () {
                $("#btnSubmit").attr('disabled', false);
                $(".fa-pulse").css('display', 'none');
            },
            success: function (response) {
                // console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $(".cancel_order")[0].reset();
                    location.reload();

                }
            },
            error: function (error) {
                // console.log(error);
            }
        });
    })



    $(document).on('submit', '.btnReject', function (e) {
        var id = $(this).attr('id');
        var dbid = $(this).attr('dbid');
        e.preventDefault();
        $.ajax({
            url: "/api/order/request/cancel/update",
            type: "post",
            data: {
                id: id,
                dbid: dbid
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
                    // btn.css('display','none');
                    location.reload();

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    $(document).on('click', '.btnApprove', function (e) {
        var id = $(this).attr('id');
        var dbid = $(this).attr('dbid');
        e.preventDefault();
        $.ajax({
            url: "/api/order/request/cancel/update",
            type: "post",
            data: {
                id: id,
                dbid: dbid
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
                    // btn.css('display','none');
                    location.reload();

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

</script>
@endsection
