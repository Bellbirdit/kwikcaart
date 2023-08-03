@extends('layout/master')
@section('title')
Safeer | Staff
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Staff cards</h2>
        <div>
            <a href="{{ url('add/admin/staff') }}" class="btn btn-primary"><i
                    class="material-icons md-plus"></i> Create new</a>
        </div>
    </div>
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-3 ">
                    <input type="text" placeholder="Search by name" class="form-control" id="search" />
                </div>
                <div class="col-lg-3">
                    <input type="text" placeholder="Search by email" class="form-control" id="filterEmail" />
                </div>
                <div class="col-lg-2 ">
                    <select class="form-select" id="filterStatus">
                        <option value="All" selected>Choose Status</option>
                        <option value="active">Active only</option>
                        <option value="inactive">Disabled</option>
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
        <!-- card-header end// -->
        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4" id="divData">

            </div>
            <!-- row.// -->
        </div>
        <!-- card-body end// -->
    </div>
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i>
            {{ __('Data is being loading.. It might take few seconds') }}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
            {{ __('No Record Found') }} !</h6>
    </div>
    <div class="col-12">
        <div id="divPagination" class="">
            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {

        

        $(document).on('click', '.login', function (e) {
            var id = $(this).attr('id')
            $.ajax({
                url: '/api/admin/stafflogin/' + id,
                type: "get",
                dataType: "JSON",
                success: function (response) {

                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])

                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                        window.location.href="/dashboard";
                     
                    }
                },
                error: function (error) {
                    console.log(error);
                },
                async: false
            });
        });



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
                url: '/api/admin/staff/count',
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
                url: '/api/admin/staff/list',
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

        $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this User!",
                    type: "warning",
                    buttons: true,
                    confirmButtonColor: "#ff5e5e",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    dangerMode: true,
                    showCancelButton: true
                })
                .then((deleteThis) => {
                    if (deleteThis.isConfirmed) {
                        $.ajax({
                            url: '/api/delete-user/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete User.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "User has been deleted.",
                                        "success");
                                    userCount()
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
    });

</script>

@endsection
