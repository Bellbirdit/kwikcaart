@extends('layout/master')
@section('title')
Kwikcaart | Store Staff
@endsection
@section('content')

<section class="content-main">
    <div class="row">
        <div class="col-12">
            <div class="content-header">
                <h2 class="content-title">Add New Staff</h2>
                <!-- <div>
                    <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button>
                    <button class="btn btn-md rounded font-sm hover-up">Publich</button>
                </div> -->
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Staff information</h4>
                    <b>Please fill all the given information</b>
                </div>
                <div class="card-body">
                    <form id="store_form">

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label class="form-label">Full Name<span
                                            class="text-danger">*</span></label>
                                    <input placeholder="Enter full name" type="text" name="name" id="name"
                                        class="form-control" required />
                                </div>
                            </div>


                            <?php
                            $roles = Spatie\Permission\Models\Role::where('name','=','Store')->where('store_id',auth()->user()->code)->get(); 
                            $types= App\Models\UserType::all();  ?>
                            <div class="col-lg-6">
                                <label class="form-label">Role<span class="text-danger">*</span></label>
                                <select class="form-control" name="type" id="roleType" required>
                                    <option value="" selected>--select role type--</option>
                                    @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <select class="form-control" name="role" id="role" required>
                                    <option value="" selected>--select role first--</option>

                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input placeholder="example@example.com" type="email" name="email" id="email"
                                    class="form-control" required />
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Password<span class="text-danger">*</span></label>
                                <input placeholder="********" type="password" name="password" id="password"
                                    class="form-control" required />
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Contact Number<span class="text-danger">*</span></label>
                                <input type="text" name="contact" id="contact" class="form-control" required />
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label imageChange">Picture</label>
                                <input type="file" name="avatar" id="avatar" class="form-control" />
                            </div>

                            <div class="col-lg-12">
                                <label class="form-label">Address<span class="text-danger">*</span></label>
                                <textarea name="address" id="address" cols="30" rows="2" class="form-control"
                                    required></textarea>
                            </div>
                           
                        </div>
                        <div class="col-12 mb-4 text-end mt-4">
                            <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i
                                    class="fas fa-spinner fa-spin" style="display: none;"></i> Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $(document).ready(function (e) {
        $(document).on('change', '.imageChange', function (e) {
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();
                    reader.onload = function (e) {
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

        $("#store_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/staff/add',
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
                        $("#store_form")[0].reset();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });
    $(document).on('change','#roleType',function(){
            var id = $(this).val();
            $.ajax({
                url: '/api/role/types',
                type: "POST",
                data: {id:id},
                dataType: "JSON",
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
                        $("#role").html(response["html"])
                    } else if (response["status"] == "success") {
                        $("#role").html(response["html"])
                        
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
</script>

@endsection
