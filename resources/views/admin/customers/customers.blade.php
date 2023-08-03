@extends('layout/master')
@section('title')
Safeer | All Customers
@endsection
@section('content')
<main class="container-fluid">
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">Customers list</h2>
        </div>
        <div class="card mb-4">
            <header class="card-header">
                <div class="row gx-3">
                    <div class="col-lg-4">
                        <input type="text" placeholder="Search by name" class="form-control" id="search" />
                    </div>
                    <div class="col-lg-4">
                        <input type="text" placeholder="Search by email" class="form-control" id="filterEmail" />
                    </div>
                    <!-- <div class="col-lg-2 ">
                        <select class="form-select" id="filterStatus">
                            <option value="All" selected>Choose Status</option>
                            <option value="active">Active only</option>
                            <option value="inactive">Disabled</option>
                        </select>
                    </div> -->
                    <div class="col-lg-2">
                        <a href="javascript:;" class="btn btn-primary btn-block" id="btnFilter">FILTER</a>
                    </div>
                    <div class="col-lg-2">
                        <a href="" class="btn btn-danger btn-block" id="btnReset">RESET</a>
                    </div>

                </div>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th>Registered since</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Emirate</th>
                                <th>Total Order</th>
                                <th>Amount</th>
                                <th class="text-end">View</th>
                            </tr>
                        </thead>
                        <tbody id="divData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
            <span>
                <i class="fas fa-spinner fa-spin"></i>
                {{ __('Customers are being loading.. It might take few seconds') }}.
            </span>
        </div>
        <div class="row text-center" id="divNotFound" style="display:none">
            <h6 class="mt-lg-5" style="color:red;"><i class="bx bx-window-close"></i>
                {{ __('No Customer Found') }} !</h6>
        </div>
        <div class="col-12 mb-3">
            <div id="divPagination" class="">
                <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
            </div>
        </div>
    </section>

</main>
@endsection
@section('scripts')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var filterLength = 1;
        var total = 0;
        var filterTitle = $("#search").val();
        var contentPagination = $("#content-pagination");
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#divData");
        var filterEmail = $("#filterEmail").val();
        var filterStatus = $("#filterStatus").val();

        function setFilters() {
            filterTitle = $("#search").val()
            filterEmail = $("#filterEmail").val();
            filterStatus = $("#filterStatus").val();
            filterLength = 9;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/admin/customer/count',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    filterEmail: filterEmail,
                    filterStatus: filterStatus
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function () {},
                complete: function () {},
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "success") {
                        total = response["data"];
                        initPagination(Math.ceil(total / filterLength));
                    } else if (response["status"] == "fail") {
                        $("#divNotFound").css('display', 'block')
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function Storelist(offset, filterLength) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/admin/customer/list',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    offset: offset,
                    filterEmail: filterEmail,
                    filterStatus: filterStatus
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                        $("#divNotFound").css('display', 'block')
                    } else if (response["status"] == "success") {
                        $("#divNotFound").css('display', 'none')
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'contents')
                        $("#divData").append(response["rows"])
                        $("#content-pagination").css('display', 'flex')
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function initPagination(totalPages) {

            if (totalPages > 0) {
                contentPagination.show();
                contentPagination.twbsPagination({
                    totalPages: totalPages,
                    visiblePages: 4,
                    onPageClick: function (event, page) {
                        Storelist((page === 1 ? page - 1 : ((page - 1) * filterLength)),
                            filterLength);
                    }
                });
            } else {

                contentPagination.hide();
                contentFound.hide();
                contentNotFound.show();
            }
        }

        $(document).on('keyup', '#search', function () {
            $("#divData").html('')
            setFilters()
            contentPagination.twbsPagination('destroy');
            Count()
        });
        $(document).on('click', '#btnFilter', function (e) {
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            Count()
        })


        $(document).on('click', '#btnReset', function (e) {
            $("#search").val('')
            $("#filterEmail").val('')
            $("#filterStatus").val('All')
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            Count()
        })
    });

</script>

@endsection
