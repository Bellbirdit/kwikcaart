@extends('layout/master')

@section('title')
Safeer | Order Report
@endsection

@section('content')
<div class="container-fluid ">
    <div class="page-content">
        <h6 class="mb-0 text-uppercase">Generate Report</h6>
        <!-- filters -->
        <div class="card mt-5 shadow-none">
            <div class="card-body">
                <div class="container">
                    <form id="export_form">
                        <div class="row mt-2 mb-3">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2">
                                @php
                                    $result = App\Models\User::query();
                                    $result = $result->whereHas('roles', function ($q) {
                                    $q->where('name', 'Store');
                                    });
                                    $storess = $result->orderBy('id', 'DESC')->get();
                                @endphp

                                <label class="fw-bold mb-1" style="font-size: 12px">Select Store</label>
                                <select class="form-control select2" required id="filterStore">
                                    <option selected value="all">All</option>
                                    @foreach($storess as $usr)
                                        <option value="{{ $usr->code }}">{{ ucwords($usr->name) }} </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2">
                                <label class="fw-bold mb-1" style="font-size: 12px">Status</label>
                                <select id="filterStatus" name="order_status" required class="form-select">
                                    <option value="all">All</option>
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

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2">
                                <label class="fw-bold mb-1" style="font-size: 12px">Select Date</label>
                                <input type="text" class="form-control" name="filterDate" id="filterDate" value="" />
                                <input type="hidden" name="filterFromDate" id="filterFromDate" value="">
                                <input type="hidden" name="filterToDate" id="filterToDate">

                            </div>
                            <div class="col-8"></div>
                            <div class="col-4 mt-3 text-end">
                                <button type="submit" class="btn btn-success btn-block w-100 mt-2" id="btnExport">
                                    <i class="bx bx-loader-circle bx-spin bx-exp" style="display: none "></i>
                                    Generate Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a href="/uploads/content" id="fileDownload" fileName="" download="data"></a>
    </div>
</div>
@endsection

@section("scripts")
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var filterLength = 1;
        var total = 0;
        var filterStore = $("#filterStore").val();
        var filterStatus = $("#filterStatus").val();

        var contentPagination = $("#content-pagination");
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#divData");

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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);
        cb(start, end);

        function dateFilters() {
            filterFromDate = $("#filterFromDate").val();
            filterToDate = $("#filterToDate").val();
            filterDate = $("#filterDate").val();
            filterStore = $("#filterStore").val();
            filterStatus = $("#filterStatus").val();


        }

        $("#export_form").on('submit', function (e) {
            e.preventDefault()
            dateFilters()

            $.ajax({
                url: '/api/order-report/',
                type: "get",
                data: {
                    filterFromDate: filterFromDate,
                    filterToDate: filterToDate,
                    filterStore: filterStore,
                    filterStatus: filterStatus,

                },
                dataType: "JSON",

                beforeSend: function () {
                    $("#btnExport").attr('disabled', true)
                    $(".bx-exp").css('display', 'inline-block')
                },
                complete: function () {
                    $("#btnExport").attr('disabled', false)
                    $(".bx-exp").css('display', 'none')
                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('error', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Exported', response["msg"])
                        $("#fileDownload").attr('href', '/uploads/content/' + response[
                            "data"])
                        $("#fileDownload").attr('fileName', response["data"]);

                        $("#fileDownload").click()
                        // $("#fileDownload").attr('href','')
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });

        });
        $(document).on('click', '#fileDownload', function (e) {
            e.preventDefault(); //stop the browser from following
            var fileUrl = $(this).attr('href');

            fetch(fileUrl)
                .then(resp => resp.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    // the filename you want
                    a.download = $(this).attr('fileName');
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    deleteFile()
                })

        });

        function deleteFile() {
            var filename = $("#fileDownload").attr('fileName');
            
            $.ajax({
                url: '/api/delete-report/'+ filename,
                type: "get",
                data: {
                    filename: filename
                },
                dataType: "JSON",
                success: function (response) {
                    console.log(response)
                },
                error: function (error) {

                },
                async: false
            });
        }

    });

</script>
@endsection
