@extends('layout/master')
@section('title')
Kwikcaart | Profile
@endsection
@section('content')

<section class="content-main">
    <div class="main-container container-fluid">
        <div class="content-header">
            <div>
                <h2 class="content-title card-title">Personal detail</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                @if(auth()->user()->avatar != '')
                                    <div class="">
                                        <span class="">
                                            <img class="br-5 rounded-circle" alt=""
                                                src="{{ asset('/uploads/files/'.auth()->user()->avatar) }}"
                                                style="width:200px !important;">
                                        </span>
                                    </div>
                                @else
                                    <div class="">
                                        <span class="">
                                            <img class="br-5" alt=""
                                                src="{{ asset('assets/imgs/people/avatar-2.png') }}"
                                                style="width:50% !important;">
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-10">
                                <a href="/profile/e/" class="btn btn-success float-end text-white">Edit
                                    profile</a>
                                <p class="tx-13 text-muted ms-md-4 ms-0 mb-2 pb-2 ">
                                    <span class="me-3">
                                        <i class="far fa-address-card me-2"></i>
                                        {{ auth()->user()->name }}
                                    </span>
                                </p>

                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-mobile me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Phone:
                                        {{ auth()->user()->contact }}</span>
                                </p>

                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-envelope me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Email:</span>
                                    {{ auth()->user()->email }}
                                </p>
                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-envelope me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Address:</span>
                                    {!! nl2br(auth()->user()->address) !!}
                                </p>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="h6">Change Password</div>
                    </div>
                    <div class="card-body">
                        <form id="change_password">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <div>

                                    <input class=" form-control" type="password" required name="current_password"
                                        placeholder="Current Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <div>

                                    <input class=" form-control" type="password" required name="new_password"
                                        placeholder="New Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <div>

                                    <input class="input100 form-control" type="password" required
                                        name="confirm_password" placeholder="Confirm Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="row">
                                <div class="col-12 text-center mt-3">
                                    <button type="submit" id="btnSubmitPassword"
                                        class="btn btn-success text-white px-4">
                                        <i class="fa fa-spin fa-spinner" id="bx-pass" style="display: none"></i> Save
                                        Password</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
   
</section>
@endsection
@section('scripts')





<script>
    $(document).ready(function (e) {

        // change Password
        $("#change_password").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/change/password',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmitPassword").attr('disabled', true);
                    $("#bx-pass").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmitPassword").attr('disabled', false);
                    $("#bx-pass").css('display', 'none');
                },
                success: function (response) {
                    // console.log(response);
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                        $("#change_password")[0].reset();
                    }

                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));
    });

</script>

@endsection
