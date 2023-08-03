@extends('layout/master')
@section('title')
Safeer | Products
@endsection
@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Products grid</h2>
        </div>
    </div>
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-3 ">
                    <input type="text" placeholder="Search by name" class="form-control" id="search" />
                </div>
                <div class="col-lg-3">
                    <input type="text" placeholder="Search by Barcode" class="form-control" id="filterCode" />
                </div>
                <div class="col-lg-2 ">
                    <select class="form-select" id="filterStatus">
                        <option value="All" selected>Choose Status</option>
                        <option value="yes">In Stock</option>
                        <option value="no">No Stock</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <a href="javascript:;" class="btn btn-primary btn-block" id="btnFilter">FILTER</a>
                </div>
                <div class="col-lg-2">
                    <a href="" class="btn btn-danger btn-block" id="btnReset">RESET</a>
                </div>
            </div>
        </header>

        <div class="container-fluid">

            <div class="content-header">
                <div>
                    <h2 class="content-title card-title">Products List</h2>
                    <!-- <p>Lorem ipsum dolor sit amet.</p> -->
                </div>

            </div>
            <div class="card mb-4">

                <div class="card-body">
                    <article class="itemlist">
                        <div class="row align-items-center">
                            <div class="col-lg-4 col-sm-2 col-4 col-price">
                                <h5>Product</h5>
                            </div>
                            <div class="col-lg-3 col-sm-2 col-4 col-price">
                                <h5>Price & Offer</h5>
                            </div>
                            <div class="col-lg-3 col-sm-2 col-4 col-price">
                                <h5>Details & Stock</h5>
                            </div>
                            <div class="col-lg-2 col-sm-1 col-4 col-status">
                                <h5>Published</h5>
                            </div>

                        </div>
                    </article>
                    <article class="itemlist">
                        <div class="row align-items-center " id="divData">

                        </div>
                    </article>
                </div>
            </div>
        </div>

    </div>
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i>
            {{ __('Product are being loading.. It might take few seconds') }}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
            {{ __('No Product Found') }} !</h6>
    </div>
    <div class="col-12 mb-3">
        <div id="divPagination" class="">
            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
        </div>
    </div>
</section>


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
        var filterCode = $("#filterCode").val();
        var filterStatus = $("#filterStatus").val();
        var importFile = "";

        function setFilters() {
            filterTitle = $("#search").val()
            filterCode = $("#filterCode").val();
            filterStatus = $("#filterStatus").val();
            filterLength = 12;
        }
        storecount();

        function storecount() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/store/product/count',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    filterCode: filterCode,
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

        function productlist(offset, limit) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/store/product/list',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    limit: limit,
                    offset: offset,
                    filterCode: filterCode,
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
                        $("#divData").css('display', 'flex')
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
                        productlist((page === 1 ? page - 1 : ((page - 1) * filterLength)),
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
            storecount()
        });
        $(document).on('click', '#btnFilter', function (e) {
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            storecount()
        })
        $(document).on('click', '#btnReset', function (e) {
            $("#search").val('')
            $("#filterCode").val('')
            $("#filterStatus").val('All')
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            storecount()
        })
    });

</script>
@endsection
