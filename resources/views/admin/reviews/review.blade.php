@extends('layout/master')
@section('title')
Kwikcaart | Product Reviews
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Reviews</h2>
            <!-- <p>Lorem ipsum dolor sit amet.</p> -->
        </div>
        <div>
            <input type="text" name="name" placeholder="Search by name" class="form-control bg-white" />
        </div>
    </div>
    <div class="card mb-4">
        <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <input type="text" name="query" placeholder="Search..." class="form-control" />
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <select name="status" class="form-select">
                        <option>Status</option>
                        <option>Active</option>
                        <option>Disabled</option>
                        <option>Show all</option>
                    </select>
                </div>
            </div>
        </header>
        <!-- card-header end// -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" />
                                </div>
                            </th>
                           
                            <th>Product</th>
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
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
            {{ __('Reviews is being loading.. It might take few seconds') }}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
            {{ __('No data found in reviews') }} !</h6>
    </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function list(name = "", query = "", status = "", limit = "") {
        $("#divLoader").css('display', 'block')
        $("#divData").css('display', 'none')
        $("#divNotFound").css('display', 'none')
        $("#divData").html('');
        $.ajax({
            url: '/api/reviews/list?q='+query+"&name="+name+"&status="+status+"&limit="+limit,
            type: "get",

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
                    $("#divData").html(response["rows"])

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
    $("input").on("keyup", function(){
        let name = $("input[name=name]").val();
        let query = $("input[name=query]").val();
        let status = $("select[name=status]").val();
        let limit = $("select[name=limit]").val();
        list(name, query, status, limit);
    });
    $("select").on("change", function(){
        let name = $("input[name=name]").val();
        let query = $("input[name=query]").val();
        let status = $("select[name=status]").val();
        let limit = $("select[name=limit]").val();
        list(name, query, status, limit);
    });
    $(document).ready(function (e) {
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#divData");
        list()
        
    });
</script>
<script>
    $(document).on('change', '.reviewStatus', function (e) {
            var status = $(this).prop('checked') == true ? '1' : '0';
            var data_id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/review/status',
                data: {
                    status: status,
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
    $(document).on('click', '.delete_review', function (e) {
        var id = $(this).attr('id')
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Review!",
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
                        url: '/reviews/delete/' + id,
                        type: "get",
                        dataType: "JSON",
                        success: function (response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete review.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Review has been deleted.",
                                    "success");
                                location.reload();
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

</script>
@endsection