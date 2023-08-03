@extends('layout/master')
@section('title')
Safeer | Edit Profile
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Personal detail</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0 h6 ">Basic Info</h5>
        </div>
        <div class="card-body">
        <input type="hidden" name="id" value="{{auth()->user()->id}}"/>
            <form  id="profile_form">
                <input type="hidden" name="id" />
                <div class="form-group row mt-3">
                    <label class="col-md-2 col-form-label">Your name</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="Your name" name="name" id="name" value="{{auth()->user()->name}}" >
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label class="col-md-2 col-form-label">Your Email</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="Your email" name="email" id="email" value="{{auth()->user()->email}}">
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label class="col-md-2 col-form-label">Your Phone</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" placeholder="Your Phone" name="contact" id="contact" value="{{auth()->user()->contact}}">
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label class="col-md-2 col-form-label">Photo</label>
                    <div class="col-md-10">
                        <input type="file" name="avatar" id="avatar" value="{{auth()->user()->avatar}}" class="form-control">
                    </div>
                </div>
                <div class="form-group row mt-3">
                    <label class="col-md-2 col-form-label">Address</label>
                    <div class="col-md-10">
                        <textarea name="address" id="address" class="form-control" cols="30" rows="5" style="width:100%">{{auth()->user()->address}}</textarea>
                    </div>
                </div>

                <div class="form-group mb-0 text-end mt-4">
                <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function(e) {
        

        $("#profile_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/profile/update',
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
                    window.location.href = "/profile/v/" + '/';

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