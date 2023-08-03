@extends('layout/master')
@section('title')
Safeer | Home Page Setting
@endsection
@section('content')


<div class="container-fluid">
    <div class="row m-2">
        <div class="breadcrumb-header justify-content-between">
            <div class="justify-content-center mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item tx-15"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Home Page Settings</li>
                </ol>
            </div>
        </div>




        
        <div class="card">
            <div class="card-heading mt-3">
                <h5>
                    Slider Setting
                </h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="homesetting_form">
                    <input type="hidden" name="id" value="{{ $homeslider->id }}" />
                    <div class="row">
                        <?php  $img1 = $homeslider->getImage($homeslider->slider); 
                             ?>
                        <div class="col-lg-12">
                            <div class="form-group row mb-2">
                                <label class="col-md-4 col-form-label">Slider</label>
                                <div class="col-md-9">
                                    <input type="file"  id="slider1" name="slider"
                                        class="form-control" value="{{$homeslider->slider1}}">
                                        <img src="{{ asset('uploads/files/'.$img1) }}"
                                                    alt="" style="width:60px;">
                                </div>
                            </div>
                        </div>
                   

                    </div>
                    <div class="row">
                     
                        <div class="col-lg-6">
                            <div class="form-group row mb-2">
                                <label class="col-md-4 col-form-label">Slider Heading</label>
                                <div class="col-md-9">
                                    <input type="text" placeholder="heading" id="heading" name="heading"
                                        class="form-control" value="{{ $homeslider->heading }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row mb-2">
                                <label class="col-md-4 col-form-label">Slider Text</label>
                                <div class="col-md-9">
                                    <input type="text" placeholder="heading" id="text" name="text"
                                        class="form-control" value="{{ $homeslider->text }}">
                                </div>
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
                url: '/api/homepage/update',
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
                        window.location.href = "/view/slider";
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
