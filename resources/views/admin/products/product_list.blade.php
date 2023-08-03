@extends('layout/master')

@section('title')
Safeer | Product list
@endsection

@section('content')



<section class="content-main">
    
    <div class="content-header m-0">
        <div>
            <h2 class="content-title card-title">Products List</h2>
        </div>
        <div>
            <!--<a href="#" class="btn btn-primary rounded font-md">Export</a>-->
            <a href="javascript:;" class="btn btn-primary rounded font-md btnImport"><i class="fas fa-spinner fa-spin fa-import" style="display:none;"></i> Import</a>
            <a href="{{ url('product/create') }}" class="btn btn-primary btn-sm rounded">Create new</a>
        </div>
    </div>
    <input type="file" name="product_excel" id="importFile" accept=".xls, .xlsx, .csv" style="opacity: 0;" />
    <div class="card mb-4 mt-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 ">
                    <input type="text" placeholder="Search by name" class="form-control" id="search" />
                </div>
                <div class="col-lg-4">
                    <input type="text" placeholder="Search by Barcode" class="form-control" id="filterCode" />
                </div>
                <div class="col-lg-2 ">
                    <select class="form-select" id="filterStatus">
                        <option value="All" selected>Choose Status</option>
                        <option value="yes">In Stock</option>
                        <option value="no">No Stock</option>
                    </select>
                </div>
                <div class="col-lg-1">
                    <a href="javascript:;" class="btn btn-primary btn-block" id="btnFilter">Filter</a>
                </div>
                <div class="col-lg-1">
                    <a href="" class="btn btn-danger btn-block" id="btnReset">Reset</a>
                </div>
            </div>
        </header>

      <div class="container-fluid">
                <div class="card mb-4">
     
                    <div class="card-body">
                        <article class="itemlist">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-sm-2 col-4 col-price">
                                    <h5>Product</h5>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-4 col-price">
                                    <h5>Price & Offer</h5>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-4 col-price">
                                    <h5>Details & Stock</h5>
                                </div>
                                <div class="col-lg-2 col-sm-1 col-4 col-status">
                                    <h5>Publish</h5>
                                </div>
                                <div class="col-lg-2 col-sm-2 col-4 col-status">
                                    <h5>Action</h5>
                                </div>
                            </div>
                        </article>
                        <article class="itemlist" >
                        <div class="row align-items-center " id="divData">

                        </div>
                        </article>
                    </div>
                </div>
            </div>




        



        <!-- <div class="card-body">
            <div class="row gx-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 row-cols-xxl-5" id="divData">

            </div>
        </div> -->
    </div>
  
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i> {{__('Product are being loading.. It might take few seconds')}}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i> {{__('No Product Found')}} !</h6>
    </div>
    <div class="col-12 mb-3">
        <div id="divPagination" class="">
            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
        </div>
    </div>
    <!-- card end// -->
</section>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
        $(document).ready(function (e) {
        $(document).on('change', '.productStock', function (e) {
            // alert('sdsdf')
            var published = $(this).prop('checked') == true ? '1' : '0';
            var data_id = $(this).attr('data-id');
            // alert(status)
            // alert(user_id) 
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/product/status',
                data: {
                    published: published,
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
<script type="text/javascript">
    $(document).ready(function(e) {
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
            filterLength = 10;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/product/count',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    filterCode: filterCode,
                    filterStatus: filterStatus
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
                        $("#divData").css('display', 'none')
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function Productlist(offset, filterLength) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/product/list',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    offset: offset,
                    filterCode: filterCode,
                    filterStatus: filterStatus
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
                    } else if (response["status"] == "success") {
                        $("#divNotFound").css('display', 'none')
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'flex')
                        $("#divData").append(response["rows"])
                        $("#content-pagination").css('display', 'flex')
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
        // delete
        $(document).on('click', '.btnDelete', function(e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Product!",
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
                            url: '/api/product/delete/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function(response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete product.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Product has been deleted.",
                                        "success");
                                    Count()
                                }
                            },
                            error: function(error) {
                                // console.log(error);
                            },
                            async: false
                        });
                    } else {
                        Swal.close();
                    }
                });
        });

        function initPagination(totalPages) {

            if (totalPages > 0) { 
                contentPagination.show();
                contentPagination.twbsPagination({
                    totalPages: totalPages,
                    visiblePages: 4,
                    onPageClick: function(event, page) {
                        Productlist((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);
                    }
                });
            } else {

                contentPagination.hide();
                contentFound.hide();
                contentNotFound.show();
            }
        }

        $(document).on('keyup', '#search', function() {
            $("#divData").html('')
            setFilters()
            contentPagination.twbsPagination('destroy');
            Count()
        });
        $(document).on('click', '#btnFilter', function(e) {
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            Count()
        })


        $(document).on('click', '#btnReset', function(e) {
            $("#search").val('')
            $("#filterCode").val('')
            $("#filterStatus").val('')
            setFilters()
            $("#divData").html('')
            contentPagination.twbsPagination('destroy');
            Count()
        })

      $(document).on('click', '.btnImport', function() {
            $("#importFile").click();
        });
        $("#importFile").on('change', (function(e) {
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                importFile = e.target.files[0];
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();
                    reader.readAsDataURL(e.target.files[0]);
                    importProduct()



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

        function importProduct(file) {
            var formData = new FormData();


            if (importFile != "") {

                formData.append('file', importFile);
            } else {
                toastr.error('Failed', 'File Not Found');
                return;

            }
            $.ajax({
                url: '/api/product/import',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "JSON",
                cache: false,
                beforeSend: function() {
                    $(".btnImport").attr('disabled', true);
                    $(".fa-import").css('display', 'inline-block');
                },
                complete: function() {
                    $(".btnImport").attr('disabled', false);
                    $(".fa-import").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


    });

    $(document).on('change', '.toggle-class', function (e) {
        // alert('sdsdf')
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var coupon_id = $(this).data('id'); 
        // alert(status)
        // alert(user_id)
        $.ajax({
            type: "POST",
            dataType: "json",
            url: '/api/change/coupon/status',
            data: {'status': status, 'coupon_id': coupon_id},
            success: function(response){
            if (response["status"] == "fail") {
                toastr.error('Failed', response["msg"])
            } else if (response["status"] == "success") {
                toastr.success('Success', response["msg"])
                
            }
            }
        });
    })
</script>
@endsection