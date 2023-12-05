@extends('layout/master')
@section('title')
Kwikcaart | Home Page Slider
@endsection
@section('content')


<div class="container-fluid">
    <div class="row m-2">
        <div class="breadcrumb-header justify-content-between">
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item tx-15"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Home Page Slider</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-heading mt-3">
                <h5>
                    Add Slider
                </h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="homesetting_form">
                    <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row mb-2">
                            <label class=" col-form-label">Slider Heading</label>
                            <div class="">
                                <input type="text" placeholder="Enter main heading" id="heading" name="heading"
                                    class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row mb-2">
                            <label class=" col-form-label">Slider Text</label>
                            <div class="">
                                <input type="text" placeholder="Enter sub heading" id="" name="text"
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group row mb-2">
                            <label class="">Slider Image</label>
                            <div class="">
                                <input type="file" id="slider1" name="slider" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-spinner fa-spin"
                                style="display: none;"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<script>
    $(document).ready(function (e) {

        $("#homesetting_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/slider/add',
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
