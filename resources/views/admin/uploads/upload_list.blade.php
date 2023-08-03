@extends('layout/master')

@section('title')
Gallery list
@endsection

@section('content') 
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Gallery</h2>
            <p>Gallery management</p>
        </div>
        <div>
            <a href="{{ url('upload/add') }}" class="btn btn-primary"><i
                    class="text-muted material-icons md-post_add"></i>Add New Upload</a>
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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-6 row-cols-xl-6" id="divData">

            </div>
            <!-- row.// -->
        </div>
        <!-- card-body end// -->
    </div>
    <!-- card end// -->
 
    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i> {{__('Gallery is being loading.. It might take few seconds')}}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i> {{__('No items are found in gallery')}} !</h6>
    </div>
    <div class="col-12 mb-3">
        <div id="divPagination" class="">
            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>
        </div>
    </div>
    
</section>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            filterLength = 24;
        }
        Count();

        function Count() {
            setFilters()
            contentPagination.twbsPagination('destroy');
            $.ajax({
                url: '/api/gallery/count',
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



function list(offset, filterLength) {

    setFilters()
    $("#divLoader").css('display', 'block')
    $("#divData").css('display', 'none')
    $("#divNotFound").css('display', 'none')
    $("#divData").html('');
    $.ajax({
        url: '/api/gallery/list',
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
            list((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);
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
                    text: "You will not be able to recover this item!",
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
                            url: '/api/delete/gallery/item/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete item.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Item has been deleted.",
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
   

    function copyUrl(e) {
        // alert()
			var url = $(e).data('url');
			var $temp = $("<input>");
		    $("body").append($temp);
		    $temp.val(url).select();
            try {
			    document.execCommand("copy");
			    toastr['success'](
            'Copied Url',
            'Success!', {
                closeButton: true,
                tapToDismiss: false,
                // rtl: isRtl
            });
			} catch (err) {
			}
            
		    $temp.remove();
		}
    
</script>

@endsection
