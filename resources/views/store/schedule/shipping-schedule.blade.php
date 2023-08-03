@extends('layout/master')
@section('title')
Safeer | Shipping Schedule
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">product Shipping Slot Management </h2>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row gx-5">

                <div class="col-lg-12">
                    <section class="content-body p-xl-4">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row gx-3">

                                 

                                    <div class="col-lg-3 mb-3">
                                        <a href="#" class="btn btn-icon btn-primary" data-bs-target="#shippingdate"
                                        data-bs-toggle="modal">
                                        <i data-bs-toggle="tooltip" data-bs-title="Add
                                            Date"></i>Add Date
                                    </a>
                                    </div>
                                    <div class="col-lg-3 mb-3">
                                        <a href="#" class="btn btn-icon btn-primary" data-bs-target="#shippingtime"
                                        data-bs-toggle="modal">
                                        <i data-bs-toggle="tooltip" data-bs-title="Add
                                            Date"></i>Add Time
                                    </a>
                                    </div>
                                    
                                    <!-- <div class="col-lg-3 mb-3">
                                        <button type="submit" class="btn btn-md rounded font-sm hover-up"
                                            id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                            Save </button>
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <hr class="my-2" />
                        
                        <?php
                         if (Auth::user()->hasRole('Store')){
                            $shippingschedule = App\Models\StoreShippingSchedule::where('store_id',Auth::user()->code)->latest()->get();
                         } else{
                            $shippingschedule = App\Models\StoreShippingSchedule::where('store_id',Auth::user()->store_id)->latest()->get(); }?>

                        <div class="row d-flex">
                            @foreach($shippingschedule as $scheduale)
                           
                                <h5 class="py-2">Slot Date : {{ $scheduale->date }} 
                                     <a id="{{$scheduale->id}}" href="javascript:void(0)" class="px-1 btnDelete"><i class="material-icons md-delete_forever text-danger "></i></a> </h5>

                                <?php $shippings = App\Models\ShippingTime::where('date_id',$scheduale->id)->get(); ?>
                               
                                @foreach($shippings as $shipping)
                               
                                    <div class="col-lg-3">
                                        <article class="box mb-3 bg-light text-center">
                                            <div>
                                                <h6>{{ $shipping->start_time }} </h6>
                                                <p>to</p>
                                                <h6> {{ $shipping->end_time }}</h6>
                                            </div>
                                            <!--<a class="btn  btn-light btn-sm rounded font-md" href="#">Delete</a>-->
                                            
                                            <div class="mt-2">
                                                <p> Remaining Limits {{$shipping->count}}</p>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            @endforeach


                        </div>
                        <hr class="my-2" />
                  
                    </section>
                    <!-- content-body .// -->
                </div>
                <!-- col.// -->
            </div>
            <!-- row.// -->
        </div>
        <!-- card body end// -->
    </div>
    <!-- card end// -->
</section>

<div class="modal fade" id="shippingdate">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add Date</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="shippingdate_form">
                    <div class="form-group">
                        <input type="date" class="form-control" required id="" placeholder="Enter date" name="date">
                    </div>
                    <div class="text-end">
                    <button class="btn ripple btn-primary" id="btnSubmit" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display: none"></i> Save Date
                    </button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="shippingtime">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add Time</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="shippingtime_form">
                 
                    <?php
                        $dates =App\Models\StoreShippingSchedule::where('store_id',Auth::user()->code)->orderBy('id','DESC')->get();
                    ?>
                    <div class="form-group">
                        <select name="date_id" class="form-control" required>
                            <option value="" selected> Select Date</option>
                            @foreach($dates as $date)
                                <option value="{{$date->id}}">{{$date->date}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" required id="" placeholder="Enter Time" name="start_time">
                    </div>

                    <div class="form-group">
                        <input type="time" class="form-control" required id="" placeholder="Enter time" name="end_time">
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" required id="" placeholder="Enter slot limit" name="count">
                    </div>
                    <div class="text-end">
                    <button class="btn ripple btn-primary" id="btnSubmit" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display: none"></i> Save Time
                    </button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
          $("#shippingdate_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/shipping/date/add',
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
                        $("#shippingdate_form")[0].reset();
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
        $("#shippingtime_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/shipping/time/add',
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
                        $("#shippingtime_form")[0].reset();
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));

        $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Date!",
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
                            url: '/api/delete/sschedule/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete date.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Date has been deleted.",
                                        "success");
                                        location.reload()
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
</script>

@endsection