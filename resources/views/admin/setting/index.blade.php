@extends('layout/master')
@section('title')
Kwikcaart | General Setting
@endsection
@section('content')
<style>
    .descriptionterms {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        /* number of lines to show */
        -webkit-box-orient: vertical;


    }

    .textarea {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;

        width: 100%;
    }

</style>
<div class="container-fluid">
    <div class="breadcrumb-header justify-content-between">

        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">General Settings</li>
            </ol>
        </div>
    </div>
    <!-- /breadcrumb -->
    <?php $settings = App\Models\WebSetting::where('standard_delivery', '!=', '')->get();?>

    <div class="row">
        @if(isset($settings) && sizeof ($settings) > '0')
            @foreach($settings as $setting)
                <!-- Income Tax -->
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                    <div class="card card-img-holder">
                        <div class="card-body list-icons">
                            <div class="clearfix mb-2">
                                <div class="float-start">
                                    <p class="card-text text-muted mb-1">VAT</p>
                                    <h3>{{ $setting->vat }}%</h3>
                                </div>
                                <div class="float-end">
                                    <a href="javascript:;" id="{{ $setting->id }}"
                                        class="btn btn-icon btn-success btnEditvat mt-3"
                                        data-bs-target="#editVatTexModel " data-bs-toggle="modal">
                                        <i class="fas fa-pencil tx-12"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                    <div class="card card-img-holder">
                        <div class="card-body list-icons">
                            <div class="clearfix mb-2">
                                <div class="float-start">
                                    <p class="card-text text-muted mb-1">Standard Shipping Charges</p>
                                    <h3>AED {{ $setting->standard_delivery }}</h3>
                                </div>
                                <div class="float-end">
                                    <a href="javascript:;" id="{{ $setting->id }}"
                                        class="btn btn-icon btn-success btnEditstan mt-3"
                                        data-bs-target="#editStandardModel " data-bs-toggle="modal">
                                        <i class="fas fa-pencil tx-12"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="card card-img-holder">
                <div class="card-body list-icons">
                    <div class="clearfix mb-2">
                        <div class="float-start">
                            <p class="card-text text-muted mb-1">Homepage Slider Setting</p>
                        </div>
                        <div class="float-end">
                            <a href="{{ url('view/slider') }}" data-toggle="tooltip" title="edit"
                                class="btn btn-icon btn-success" data-placement="bottom"><i
                                    class="fas fa-pencil tx-12"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="row">
    @php
     $fsettings = App\Models\FooterSetting::all();
    @endphp
    @if(isset($fsettings) && sizeof ($fsettings) > '0')
            @foreach($fsettings as $fset)
        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="card card-img-holder">
                <div class="card-body list-icons">
                    <div class="clearfix mb-2">
                        <div class="float-start">
                            <p class="card-text text-muted mb-1">Footer Setting</p>
                        </div>
                        <div class="float-end">
                            <a href="{{ url('edit/footer/setting/'.$fset->id) }}" data-toggle="tooltip" title="edit"
                                class="btn btn-icon btn-success" data-placement="bottom"><i
                                    class="fas fa-pencil tx-12"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="card card-img-holder">
                <div class="card-body list-icons">
                    <div class="clearfix mb-2">
                        <div class="float-start">
                            <p class="card-text text-muted mb-1">Web Page List</p>
                        </div>
                        <div class="float-end">
                            <a href="{{ url('all/pages') }}" data-toggle="tooltip" title="edit"
                                class="btn btn-icon btn-success" data-placement="bottom"><i
                                    class="fas fa-pencil tx-12"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start modal Income Tex -->
    <div class="modal fade" id="editVatTexModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Edit VAT</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="edit_vat">
                        @csrf
                        <input type="hidden" id="vatID" name="id">
                        <div class="form-group">
                            <input type="text" class="form-control" required id="vat" placeholder="Enter VAT"
                                name="vat">
                        </div>
                        <div class="text-end">
                            <button class="btn ripple btn-primary btnSubmit" id="btnSubmit" type="submit">
                                <i class="fas fa-spinner fa-spin" style="display: none"></i> Update VAT
                            </button>
                            <button class="btn ripple btn-secondary" data-bs-dismiss="modal"
                                type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Start modal Delivery Charges -->
    <div class="modal fade" id="editDeliveryModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Express Delivery Charges</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="edit_form">
                        @csrf
                        <input type="hidden" id="inputId" name="id">
                        <div class="form-group">
                            <input type="text" class="form-control" required id="express_delivery"
                                placeholder="Enter Delivery Charges" name="express_delivery">
                        </div>
                        <div class="text-end">
                            <button class="btn ripple btn-primary btnSubmit" id="btnSubmit" type="submit">
                                <i class="fas fa-spinner fa-spin" style="display: none"></i> Update Delivery Charges
                            </button>
                            <button class="btn ripple btn-secondary" data-bs-dismiss="modal"
                                type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editStandardModel">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Standard Delivery Charges</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="editstandard_form">
                        @csrf
                        <input type="hidden" id="inputsId" name="id">
                       
                        <div class="form-group">
                             <label>Delivery Charges</label>
                            <input type="number" class="form-control" required id="standard_delivery"
                                placeholder="Enter Delivery Charges" name="standard_delivery">
                        </div>
                          <div class="form-group">
                              <label>Limit delivery charges applicable</label>
                            <input type="number" class="form-control" required id="delivery_applicable"
                                placeholder="Enter limit" name="delivery_applicable">
                        </div>
                        
                          <div class="form-group">
                              <label>Minimum Order Limit</label>
                            <input type="number" class="form-control" required id="delivery_limit"
                                placeholder="Enter limit" name="delivery_limit">
                        </div>
                        <div class="text-end">
                            <button class="btn ripple btn-primary btnSubmit" id="btnSubmit" type="submit">
                                <i class="fas fa-spinner fa-spin" style="display: none"></i> Update Delivery Charges
                            </button>
                            <button class="btn ripple btn-secondary" data-bs-dismiss="modal"
                                type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add Privacy -->
<script type="text/javascript">
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 300,
        });
    });
    $(document).ready(function () {
        $('.summernoteterms').summernote({
            height: 300,
        });
    });

</script>

<!-- edit Express delivery charges -->
<script>
    $(document).on('click', '.btnEdit', function (e) {
        e.preventDefault();
        var charje = $(this).attr('id')
        $.ajax({
            url: '/api/edit/deliveryCharge/' + charje,
            type: "GET",
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnSubmit").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnSubmit").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    $("#express_delivery").val(response["data"]["express_delivery"]);
                    $("#inputId").val(response["data"]["id"]);

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
        $("#edit_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/update/deliveryCharge',
                type: "POST",
                data: new FormData(this),
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
                        $("#edit_form")[0].reset();
                        location.reload();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>

<!-- edit Exprstandardess delivery charges -->
<script>
    $(document).on('click', '.btnEditstan', function (e) {
        e.preventDefault();
        var standard = $(this).attr('id')
        $.ajax({
            url: '/api/edit/standardCharge/' + standard,
            type: "GET",
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnSubmit").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnSubmit").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    $("#standard_delivery").val(response["data"]["standard_delivery"]);
                    $("#delivery_limit").val(response["data"]["delivery_limit"]);
                    $("#delivery_applicable").val(response["data"]["delivery_applicable"]);
                    
                    $("#inputsId").val(response["data"]["id"]);

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
        $("#editstandard_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/update/standardCharge',
                type: "POST",
                data: new FormData(this),
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
                        $("#editstandard_form")[0].reset();
                        location.reload();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>



<!-- edit VAT Tex -->
<script>
    $(document).on('click', '.btnEditvat', function (e) {
        e.preventDefault();
        var vat = $(this).attr('id')
        $.ajax({
            url: '/api/edit/vat/' + vat,
            type: "GET",
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnSubmit").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnSubmit").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    $("#vat").val(response["data"]["vat"]);
                    $("#vatID").val(response["data"]["id"]);

                }
            },
            error: function (error) {
                console.log(error);
            }
        });

        $("#edit_vat").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/update/vat',
                type: "POST",
                data: new FormData(this),
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
                        $("#edit_vat")[0].reset();
                        location.reload();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));

    });

</script>


@endsection
