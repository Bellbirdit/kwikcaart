@extends('layout/master')

@section('title')
Brand list
@endsection

@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Brand</h2>
            <p>Brand management</p>
        </div>
        <!-- <div>
            <a href="{{ url('brand/add') }}" class="btn btn-primary"><i
                    class="text-muted material-icons md-post_add"></i>Add New Brand</a>
        </div> -->
    </div>
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-10 col-sm-12 col-md-12 mt-2">
                    <input type="text" placeholder="Search by name" class="form-control" id="search" />
                </div>
                <div class="col-lg-1 col-sm-6 col-md-6 mt-2 ">
                    <a href="javascript:;" class="btn btn-primary btn-block w-100 text-center" id="btnFilter">Filter</a>
                </div>
                <div class="col-lg-1 col-sm-6 col-md-6 mt-2  ">
                    <a href="" class="btn btn-danger btn-block w-100 " id="btnReset">Reset</a>
                </div>
            </div>
        </header>
       

      
               
               <div class="row">
                   <div class="col-lg-8">
                 
                       <div class="card m-3">
                          
                           <!-- card-header end// -->
                           <div class="card-body ">
                               <article class="itemlist">
                                   <div class="row align-items-center">
                                       
                                       <div class="col-lg-3 col-sm-2 col-4 col-price">
                                           <h5>Brands & Details</h5>
                                       </div>
                                       <div class="col-lg-3 col-sm-2 col-4 col-price">
                                           <h5>Icon</h5>
                                       </div>
                                       <div class="col-lg-3 col-sm-1 col-4 col-status">
                                           <h5>Top Brands</h5>
                                       </div>
                                       <div class="col-lg-3 col-sm-2 col-4 col-status">
                                           <h5>Action</h5>
                                       </div>
                                   </div>
                               </article>                             
                               <article class="itemlist">
                                   <div class="row align-items-center" id="divData">

                                   </div>
                               </article>
                           </div>
                       </div>
                   </div>
                   <div class="col-md-4">
                       <div class="content-header m-1 pt-3">
                           <div>
                               <h4 class="content-title card-title">Add New Brand</h4>
                           </div>
                       </div>
                       <div class="card md-4 p-3">
                       <form id="brand_form">
                               <div class="mb-2">
                                   <label for="product_name" class="form-label">Brand Name</label>
                                   <input type="text" placeholder="Type here" class="form-control" id="name" name="name" />
                               </div>
                               <div class="mb-2">
                                   <label for="product_name" class="form-label">Logo</label>
                                   <input type="file" placeholder="Type here" class="form-control" id="logo" name="logo" />
                               </div>
                               <div class="mb-2">
                                   <label for="product_name" class="form-label">Order Level</label>
                                   <input type="number" placeholder="Type here" class="form-control" id="order_level" name="order_level" />
                               </div>
                                 <div class="mb-2">
                                   <label for="product_name" class="form-label">Brand Code</label>
                                   <input type="number" placeholder="Type here" class="form-control" id="brand_code" name="brand_code" />
                               </div>
                               <div class="mb-2">
                                   <label for="product_name" class="form-label">Meta Title</label>
                                   <input type="text" placeholder="Type here" class="form-control" id="meta_itle" name="meta_title" />
                               </div>
                               <div class="mb-2">
                                   <label for="product_name" class="form-label">Meta Description</label>
                                   <textarea  id="" cols="30" rows="10" placeholder="Type here" class="meta_description form-control" name="meta_description"></textarea>
                               </div>
                               <div class="mb-2">
                                  
                                   <button type="submit" class="btn btn-primary  " id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save Brand</button>
                               </div>
                           </form>

                       </div>
                   </div>
               </div>
        
    </div>


    <!-- card end// -->
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i> {{__('Brands are being loading.. It might take few seconds')}}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i> {{__('No Brand Found')}} !</h6>
    </div>
    <div class="col-12 mb-3">
        <div id="divPagination" class="">
            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function (e) {
        $(document).on('change', '.brandTop', function (e) {
            // alert('sdsdf')
            var top = $(this).prop('checked') == true ? 0 : 1;
            var data_id = $(this).attr('data-id');
            // alert(status)
            // alert(user_id) 
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/brand/status',
                data: {
                    top: top,
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
   
    $(document).ready(function(e) {      
        $("#brand_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/brand/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#brand_form")[0].reset();
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
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

        function setFilters() {
            filterTitle = $("#search").val()
            filterLength = 9;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/brand/count',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
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

        function Brandlist(offset, filterLength) {

            setFilters()
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/brand/list',
                type: "get",
                data: {
                    filterTitle: filterTitle,
                    filterLength: filterLength,
                    offset: offset,
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

        function initPagination(totalPages) {

if (totalPages > 0) { 
    contentPagination.show();
    contentPagination.twbsPagination({
        totalPages: totalPages,
        visiblePages: 4,
        onPageClick: function(event, page) {
            Brandlist((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);
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
                    text: "You will not be able to recover this Brand!",
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
                            url: '/api/delete/brand/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete brand.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Brand has been deleted.",
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

@endsection
