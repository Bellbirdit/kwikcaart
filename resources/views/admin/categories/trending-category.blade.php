@extends('layout/master')
@section('title')
Safeer | Trending Categories
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Trending Categories</h2>
        </div>
        <div>
            <a href="javascript:;" class="btn btn-primary rounded font-md btncategoryImport" ><i class="fas fa-spinner fa-spin fa-import" style="display:none;"></i> Import</a>
        </div>
    </div>
    <input type="file" name="product_excel" id="importCat" accept=".xls, .xlsx, .csv" style="opacity: 0;" />
    <div class="card mb-4">
        <div class="card-body">
            <article class="itemlist">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-sm-2 col-4 col-price">
                        <h5>Category & Details</h5>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-4 col-status">
                        <h5>Store </h5>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-4 col-status">
                        <h5>Action</h5>
                    </div>
                </div>
            </article>
            <article class="itemlist" id="divData">
            </article>
        </div>
        <!-- card-body end// -->
    </div>
    <!-- card end// -->
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i>
            {{ __('Categories are being loading.. It might take few seconds') }}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
            {{ __('No Category Found') }}
            !</h6>
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

        function setFilters() {
            filterTitle = $("#search").val()
            filterLength = 15;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/trengingcategory/count',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
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

        function trendingCate(offset, filterLength) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/trending/category',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    offset: offset,
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
                        trendingCate((page === 1 ? page - 1 : ((page - 1) * filterLength)),
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
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            Count()
        })


        // delete
        $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Category!",
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
                            url: '/api/delete/tcategory/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete category.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Category has been deleted.",
                                        "success");
                                    Count()
                                }
                            },
                            error: function (error) {
                                // console.log(error);
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

<script>
    $(document).on('click', '.btncategoryImport', function () {
        $("#importCat").click();
    });
    $("#importCat").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            importCat = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importCategory()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {
            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importCategory(file) {
        var formData = new FormData();
        if (importCat != "") {
            formData.append('file', importCat);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/category/import',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnImport").attr('disabled', true);
                $(".fa-import").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnImport").attr('disabled', false);
                $(".fa-import").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>

@endsection
