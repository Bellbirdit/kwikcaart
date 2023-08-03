@extends('layout/master')

@section('title')
Store Edit
@endsection

@section('content')

<section class="content-main">
    <div class="row">
        <div class="col-12">
            <div class="content-header">
                <h2 class="content-title">Edit Store</h2>
                <!-- <div>
                    <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button>
                    <button class="btn btn-md rounded font-sm hover-up">Publich</button>
                </div> -->
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Update information</h4>
                    <b>Please fill all the given information</b>
                </div>
                <div class="card-body">
                    <form id="store_form">

                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label class="form-label">Store Name<span class="text-danger">*</span></label>
                                    <input placeholder="Enter store name here" type="text" name="name" id="name" class="form-control" required value="{{$store->name}}" />
                                    <input type="hidden" name="id" value="{{$store->id}}"/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Code<span class="text-danger">*</span></label>
                                <input placeholder="e1234" type="text" name="code" id="code" class="form-control" required  value="{{$store->code}}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Email Address<span class="text-danger">*</span></label>
                                <input placeholder="example@example.com" type="email" name="email" id="email" class="form-control" required value="{{$store->email}}"/>
                            </div>


                            <div class="col-lg-6">
                                <label class="form-label">Emirate</label>
                                <input placeholder="56787" type="text" name="emirate" id="emirate" class="form-control" value="{{$store->emirate}}"/>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" name="contact" id="contact" class="form-control" required value="{{$store->contact}}"/>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Store Logo</label>
                                <input type="file" name="avatar" id="avatar" class="form-control" />
                            </div>


                            <div class="col-lg-12">
                                <label class="form-label">Address<span class="text-danger">*</span></label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-control" required>{{$store->address}}</textarea>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" id="location" class="form-control" value="{{$store->location}}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Store Timing</label>
                                <input type="text" name="timing" id="location" class="form-control" value="{{$store->timing}}" />
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" value="{{$store->longitude}}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control"value="{{$store->latitude}}" />
                            </div>

                        </div>

                        <div class="col-12 mb-4 text-end mt-4">
                            <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(e) {
        $(document).on('change', '.imageChange', function(e) {
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();

                    reader.onload = function(e) {
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

        $("#store_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/store/update',
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
                        window.location.href = "/store/list";
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
    });
</script>
@endsection