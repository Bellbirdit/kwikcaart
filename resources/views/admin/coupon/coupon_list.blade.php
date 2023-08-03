@extends('layout/master')

@section('title')
Safeer | Coupon list
@endsection
@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Coupon</h2>
            <p>Coupon management</p>
        </div>
        <div>


            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i
                    class="text-muted material-icons md-post_add"></i>Add New Coupon</a>
        </div>
    </div>
    <!-- main section -->
    <div class="row">
        <div class="card mb-4">
            <!-- <header class="card-header">
            <div class="row gx-3">
                <div class="col-lg-4 col-md-6 me-auto">
                    <input type="text" placeholder="Search..." class="form-control">
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <select class="form-select">
                        <option>Status</option>
                        <option>Active</option>
                        <option>Disabled</option>
                        <option>Show all</option>
                    </select>
                </div>
                <div class="col-lg-2 col-6 col-md-3">
                    <select class="form-select">
                        <option>Show 20</option>
                        <option>Show 30</option>
                        <option>Show 40</option>
                    </select>
                </div>
            </div>
        </header> -->
            <!-- card-header end// -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover pt-4 mb-4" id="myTable">
                        <thead>
                            <tr>
                                <th>Cupon title</th>
                                <th scope="col"> Coupon type</th>
                                <th scope="col">Coupon code</th>
                                <th scope="col">Coupon value</th>
                                <th scope="col">Coupon Limit</th>

                                <th scope="col">Start Date</th>
                                <th scope="col">Expiry Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->coupon_title }}</td>
                                    <td>{{ $coupon->coupon_type }}</td>
                                    <td>{{ $coupon->coupon_code }}</td>
                                    <td>{{ $coupon->coupon_value }}</td>
                                    <td>{{ $coupon->coupon_limit }}</td>

                                    <td>{{ $coupon->start_date }}</td>
                                    <td>{{ $coupon->expiry}}</td>


                                    <td class="text-success">


                                        <label class="switch">
                                            <input data-id="{{ $coupon->id }}" class="toggle-class" type="checkbox"
                                                {{ $coupon->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>

                                    </td>

                                    <!-- @if($coupon->status == 1)
                                
                                <td class="text-danger">Deactive</td>
@endif-->
                                <td>
                                <a data-bs-effect="effect-scale" data-bs-toggle="modal"
                                                    href="#EditCoupon" class="btnEdit" id="{{ $coupon->id }}"><i
                                                        class="fa fa-edit text-primary fs-5 mx-2"></i></a>
                                    <a class=" text-danger btnDelete fs-5" id="{{ $coupon->id }}" href="javascript:void(0)"><i class="fas fa-trash "></i></a>

                                </td>
                            </tr>
@endforeach
                        </tbody>
                    </table>
                </div>
                <!-- table-responsive //end -->
                </div>
                <!-- card-body end// -->
            </div>
        </div>

        <!-- modal -->
        <div class="modal fade" id="EditCoupon" tabindex="-1" aria-labelledby="EditCouponLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditCouponLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="update_coupon">
                            @csrf
                            <input type="hidden" name="id" id="coupon_id" value="">

                            <div class="form-group mb-2">
                                <label for="">Title</label>
                                <input type="text" class="form-control coupon_title" required id=""
                                    placeholder="Enter Title" name="coupon_title">
                            </div>
                            <label for="">Coupon Code</label>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control coupon_code" required id=""
                                    placeholder="Enter Coupon Code" name="coupon_code">
                            </div>

                            <div class="form-group mb-2">
                            <label for="">Coupon Type</label>

                                <select name="coupon_type" class="form-control coupon_type" required id="">
                                    <option value="flat"> Flat Amount Coupon</option>
                                    <option value="percentage"> Flat Percentage Discount</option>
                                </select>

                            </div>
                            <label for="">Limit Value</label>

                            <div class="form-group mb-2">
                                <input type="number" class="form-control coupon_limit" required id="" placeholder="Enter Coupon Limit"
                                    name="coupon_limit">
                            </div>
                            <div class="form-group mb-2">
                            <label for="Expiry Date">Start Date</label>
                                <input type="date" class="form-control start_date" required id="" placeholder="Enter Coupon Start Date"
                                    name="start_date">
                            </div>
                            <div class="form-group mb-2">
                            <label for="Expiry Date">Expiry Date</label>
                                <input type="date" class="form-control expiry" required id="" placeholder="Enter Coupon Expiry Date"
                                    name="expiry">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Coupon Value</label>
                                <input type="number" class="form-control coupon_value" required id=""
                                    placeholder="Enter value" name="coupon_value">
                                <small class="text-success">*if flat percentage discount please do not enter percentage
                                    more
                                    than 100% </small>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn btn-primary" id="btnSubmit"> <i
                                        class="fa fa-spinner fa-pulse" style="display:none;"></i>Save changes</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modaldemo1">
            <div class="modal-dialog" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Add Coupon</h6><button aria-label="Close" class="btn-close"
                            data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="coupon_form">

                            <div class="form-group mb-2">
                                <label for="">Title</label>
                                <input type="text" class="form-control" required id="" placeholder="Enter Title"
                                    name="coupon title">
                            </div>
                            <div class="form-group mb-2">
                            <label for="">Coupppon Code</label>
                                <input type="text" class="form-control" required id="" placeholder="Enter Coupon Code"
                                    name="coupon_code">
                            </div>
                            <div class="form-group mb-2">
                            <label for="">Coupon Type</label>
                                <select name="coupon_type" class="form-control" required id="">
                                    <option value="flat"> Flat Amount Coupon</option>
                                    <option value="percentage">Percentage Discount</option>
                                </select>

                            </div>
                            <div class="form-group mb-2">
                            <label for="">Coupon Limit</label>
                                <input type="number" class="form-control coupon_limit" required id="" placeholder="Enter Coupon Limit"
                                    name="coupon_limit">
                            </div>
                            <div class="form-group mb-2">
                            <label for="">Start Date</label>
                                <input type="date" class="form-control strat_date" required id="" placeholder="Enter Coupon start Date"
                                    name="start_date">
                            </div>

                            <div class="form-group mb-2">
                            <label for="">Expiry Date</label>
                                <input type="date" class="form-control expiry" required id="" placeholder="Enter Coupon Expiry Date"
                                    name="expiry">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Coupon Value</label>
                                <input type="number" class="form-control coupon_value" required id="" placeholder="Enter value"
                                    name="coupon_value">
                                <small class="text-success">*if flat percentage discount please do not enter percentage
                                    more
                                    than 100% </small>
                            </div>

                            <div class="text-end">
                                <button class="btn ripple btn-primary" id="btnSubmit" type="submit">
                                    <i class="fa fa-spinner fa-spin" style="display: none"></i> Save Coupon
                                </button>
                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal"
                                    type="button">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




</section>

<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {

        // delete
        $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Coupon!",
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
                            url: '/api/delete/coupon/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete coupon.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Coupon has been deleted.",
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
                data: {
                    'status': status,
                    'coupon_id': coupon_id
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


    $("#coupon_form").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: "/coupon/add",
            type: "POST",
            data: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#btnSubmit").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function () {
                $("#btnSubmit").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $("#coupon_form")[0].reset();
                    location.reload();
                }
            },
        });
    }));

    // edit coupon
    $(document).on('click', '.btnEdit', function (e) {
        var coupon = $(this).attr('id');
        $.ajax({
            url: '/api/coupon/edit',
            type: "GET",
            dataType: "JSON",
            data: {
                coupon: coupon

            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    // toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    // toastr.success('Success', response["msg"])
                    $("#coupon_id").val(response["data"]["id"])
                    $(".coupon_value").val(response["data"]["coupon_value"])
                    $(".coupon_type").val(response["data"]["coupon_type"])
                    $(".coupon_code").val(response["data"]["coupon_code"])
                    $(".coupon_title").val(response["data"]["coupon_title"])
                    $(".start_date").val(response["data"]["start_date"])
                    $(".expiry").val(response["data"]["expiry"])
                    $(".coupon_limit").val(response["data"]["coupon_limit"])
                }
            },
            error: function (error) {
                console.log(error);
            },
            async: false
        });
    });

    // update coupon
    $("#update_coupon").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: '/api/coupon/update',
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#btnSubmit").attr('disabled', true);
                $(".fa-pulse").css('display', 'inline-block');
            },
            complete: function () {
                $("#btnSubmit").attr('disabled', false);
                $(".fa-pulse").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $("#Editcoupon").modal('hide');
                    location.reload();
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }));

</script>

@endsection
@section('scripts')
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js">

</script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

</script>
@endsection
