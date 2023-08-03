@extends('layout/master')

@section('title')
Categories
@endsection

@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Category List</h2>
            <p>Category Management</p>
        </div>
        <div>
            <a href="{{ url('category/create') }}" class="btn btn-primary"><i
                    class="text-muted material-icons md-post_add"></i>New Category</a>
        </div>
    </div>
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-8 col-sm-12 col-md-12 mt-2">
                    <input type="text" placeholder="Search by name" class="form-control" id="search" />
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6 mt-2 ">
                    <a href="javascript:;" class="btn btn-primary btn-block w-100 text-center" id="btnFilter">FILTER</a>
                </div>
                <div class="col-lg-2 col-sm-6 col-md-6 mt-2  ">
                    <a href="" class="btn btn-danger btn-block w-100 " id="btnReset">RESET</a>
                </div>

            </div>
        </header>
        <!-- card-header end// -->
        <div class="card-body">
            <article class="itemlist">
                <div class="row align-items-center">

                    <div class="col-lg-3 col-sm-2 col-4 col-price">
                        <h5>Category & Details</h5>
                    </div>
                    <div class="col-lg-2 col-sm-2 col-4 col-price">
                        <h5>Icon</h5>
                    </div>
                    <div class="col-lg-3 col-sm-2 col-4 col-price">
                        <h5>Banner</h5>
                    </div>
                    <div class="col-lg-2 col-sm-1 col-4 col-status">
                        <h5>Featured</h5>
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

<script>
    $(document).ready(function (e) {
        $(document).on('change', '.categoryFetaured', function (e) {
            // alert('sdsdf')
            var is_featured = $(this).prop('checked') == true ? 1 : 0;
            var data_id = $(this).attr('data-id');
            // alert(status)
            // alert(user_id)
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/category/status',
                data: {
                    is_featured: is_featured,
                    data_id: data_id
                },
                success: function (response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                    }
                }
            });
        })
    });

</script>
<script>
    $(document).ready(function () {
        if ($('.switch').is(':disabled'))
            $('[for =".switch"]').css('color', 'red');

    });

</script>
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
            filterLength = 9;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/category/count',
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

        function Storelist(offset, filterLength) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/category/list',
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
                            url: '/api/delete/category/' + id,
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
    $(document).ready(function (e) {
        $(document).on('change', '.imageChange', function (e) {
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#user_photo_selected').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);

                } else {
                    photoError = 1;
                    $("#photo-error").empty();
                    $("#btnSubmit").prop('disabled', true);

                    $("#photo-error").append(
                        '<p class="text-danger">Please upload only jpg, png format!</p>');
                }
            } else {
                $('#user_photo_selected').attr('src', '');
            }

            if (photoError == 0) {
                $(".btn-change-photo").prop('disabled', false);
                $("#btnSubmit").attr('disabled', false);
            } else {
                $(".btn-change-photo").prop('disabled', true);
            }
        });


    });

</script>

@endsection
