
@extends('frontend/layout/master')
@section('title')
Safeer | Reset Password
@endsection
@section('frontend/content')
<body class="gray-bg" cz-shortcut-listen="true">
    <div class="container-fluid mt-5">
        <div class="main-container">
            <div class="row">
                <div class="col-lg-12 text-center pt-3 pb-5">
                    <img src="{{asset('assets/logo/logo.png')}}" alt="Amor Rozgar Logo" style="height:80px" />
                </div>
            </div>
            <div class="container px-1 my-5">
                <div class="card shadow p-3 mb-5  rounded">
                    <div class="card-body">
                        <div class="d-flex justify-content-center align-items-center pb-2">
                            <img src="/assets/img/icon.png" alt="" height="60px" width="60px">
                        </div>
                        <h5 class="text-center text-muted font-weight-normal mb-4">Reset Your Password</h5>
                        <!-- Form -->
                        <form id="login_form" name="login_form">
                            <div class="row">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="hidden" name='token' value="{{$token}}">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-control" required placeholder="Enter new password" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" required class="form-control" placeholder="Confirm password" />
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center m-0">

                                        <button href="javascript:;" type="submit" class="btn btn-primary" id="btnSubmit">
                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>Change Password</button>
                                    </div>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>

                        </form>
                        <!-- / Form -->
                    </div>
                    <div class="card-footer py-3 px-4 px-sm-5">
                        <div class="text-center text-muted">
                            back to Login? <a href="/login">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script type="text/javascript">
        $("#reset_password").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/reset-password',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-pulse").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-pulse").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        window.location = "/login";
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
    </script>

@endsection