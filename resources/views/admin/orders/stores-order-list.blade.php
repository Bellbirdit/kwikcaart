@extends('layout/master')
@section('title')
Kwikcaart | Orders
@endsection

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Order List</h2>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2 mb-2">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 ">
                            <label>Order Number</label>
                            <input type="text" id="filterTitle" class="form-control" placeholder="order no">
                        </div>
                       
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                            <label>Status</label>
                            <select class="form-control select2" id="filterStatus" style="width: 100%;" tabindex="-1"
                                aria-hidden="true">
                                <option value="all" selected>Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="dispatch">Dispatched</option>
                                <option value="deliverd">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refund rejected">Refund Rejected</option>
                            <option value="Return Pending">Return Pending</option>
                            <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 mb-2">
                            <label><input type="checkBox" id="dateCheck" value="0" checked> Show All Dates
                                Record</label>
                            <input type="hidden" id="dateCheck-input" value="0">
                            <input type="text" class="form-control" name="filterDate" id="filterDate" value=""
                                disabled />
                            <input type="hidden" name="filterFromDate" id="filterFromDate" value="">
                            <input type="hidden" name="filterToDate" id="filterToDate">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-2 mt-3">
                            <a class="btn btn-primary mt-3" href="javascript:;" id="btnFilter">Filter</a>
                            <a class="btn btn-danger text-start text-right mt-3" href="javascript:;"
                                id="btnReset">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>Created Date</th>
                            <th scope="col">Order No</th>
                            <th>Customer</th>
                            <th scope="col">Amount</th>
                            <th>Payment Method</th>
                            <th scope="col">Payment Status</th>
                            <th>Order Status</th>
                            <th>Shipping Method</th>
                          
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                       
                    </tbody>
                    <tfooter>
                        <tr id="divNotFound" style="display:none;justify-content:center"
                            class="text-center justify-content-center">
                            <td colspan="7" class="text-center text-danger" style="justify-content:center"><b>No Data
                                    Found</b></td>
                        </tr>
                        <tr id="divLoader" class="text-center justify-content-center">
                            <td colspan="7" class="text-center "><i class="fas fa-spinner fa-spin"></i> Orders are bieng
                                loading, please wait for a while</td>
                        </tr>
                    </tfooter>
                </table>
            </div>
        </div>
        <div class="col-lg-12">
            <div id="divPagination" class="justify-content-center">
                <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
            </div>
        </div>
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
@endsection
@section('scripts')
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(e) {
        var filterLength = 1;
        var total = 0;
        var filterTitle = $("#filterTitle").val();
       
        var filterStatus = $("#filterStatus").val();

        var contentPagination = $("#content-pagination");
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#tBody");
        var Loader = $("#divLoader");
        var check = $("#dateCheck-input").val()

        //----filterDate----
        var filterFromDate = $("#filterFromDate").val();
        var filterToDate = $("#filterToDate").val();
        var filterDate = $("#filterDate").val();

        // Date Filter
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#filterDate span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
            filterFromDate = start.format('YYYY-MM-DD');
            filterToDate = end.format('YYYY-MM-DD');
            $("#filterFromDate").val(filterFromDate)
            $("#filterToDate").val(filterToDate)
        }
        $('#filterDate').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);

        function setFilters() {

            filterFromDate = $("#filterFromDate").val();
            filterToDate = $("#filterToDate").val();
            filterDate = $("#filterDate").val();
            filterTitle = $("#filterTitle").val();
           
            filterStatus = $("#filterStatus").val();

            check = $("#dateCheck-input").val()
            filterLength = 50;


        }

        $(document).on('change', '#dateCheck', function() {
            if ($(this).is(':checked')) {
                $("#dateCheck-input").val('0')
                $("#filterDate").attr('disabled', true);
            } else {
                $("#dateCheck-input").val('1')
                $("#filterDate").attr('disabled', false);
            }
        })
        orderCount()

        function orderCount() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/store/order/count/',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                   
                    filterStatus:filterStatus,
                    filterFromDate: filterFromDate,
                    filterToDate: filterToDate,
                    check: check
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                complete: function() {},
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "success") {
                        total = response["data"];
                        initPagination(Math.ceil(total / filterLength));
                    } else if (response["status"] == "fail") {
                        $("#divNotFound").css('display', 'block')
                        $("#divLoader").css('display', 'none')
                        $("#tBody").css('display', 'none')
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function orders(offset) {
            setFilters()

            $("#divLoader").css('display', 'block')
            $("#tBody").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#tBody").html('');
            $.ajax({
                url: '/api/store/orders',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                   
                    filterStatus: filterStatus,
                    offset: offset,
                    filterLength:filterLength,
                    filterFromDate: filterFromDate,
                    filterToDate: filterToDate,
                    check: check
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                        $("#divNotFound").css('display', 'block')
                        $("#content-pagination").css('display', 'none')
                    } else if (response["status"] == "success") {
                        $("#divNotFound").css('display', 'none')
                        $("#divLoader").css('display', 'none')
                        $("#tBody").css('display', 'contents')
                        $("#tBody").html(response["rows"])
                        $("#content-pagination").css('display', 'flex')
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function initPagination(totalPages) {
            console.log('orders is calling');
            if (totalPages > 0) {
                contentPagination.show();
                contentPagination.twbsPagination({
                    totalPages: totalPages,
                    visiblePages: 4,
                    onPageClick: function(event, page) {
                        orders((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);
                    }
                });
            } else {
                contentPagination.hide();
                contentFound.hide();
                contentNotFound.show();
                Loader.hide();
            }
        }

        $(document).on('keyup', '#filterTitle', function() {
            setFilters()
            $("#tBody").html('')
            contentPagination.twbsPagination('destroy');
            orderCount()
        });
        $(document).on('click', '#btnFilter', function(e) {
            setFilters()
            $("#tBody").html('')
            contentPagination.twbsPagination('destroy');
            orderCount()
        })


        $(document).on('click', '#btnReset', function(e) {
            $("#filterTitle").val('')
          
            $("#filterStatus").val('all')

            setFilters()
            $("#tBody").html('')
            contentPagination.twbsPagination('destroy');
            orderCount()
        })

        $(document).on('click', '.btnCancel', function() {

        var id = $(this).attr('id');
        $("#order_id").val(id);
        })

    });
</script>


<script>
    $(document).on('submit', '#cancel_order', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/cancel/order',
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
</script>
@endsection
