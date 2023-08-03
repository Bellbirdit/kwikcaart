@extends('frontend/layout/master')
@section('title')
Safeer | Reset Password
@endsection
@section('frontend/content')

<main class="main pages">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> Pages <span></span> My Account
                </div>
            </div>
        </div>
        <div class="page-content pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8 col-md-12 m-auto">
                        <div class="row">
                            <div class="heading_s1">
                                <img class="border-radius-15" src="assets/imgs/page/reset_password.svg" alt="" />
                                <h2 class="mb-15 mt-15">Set new password</h2>
                                <p class="mb-30">Please create a new password that you don’t use on any other site.</p>
                            </div>
                            <div class="col-lg-6 col-md-8">
                                <div class="login_wrap widget-taber-content background-white">
                                    <div class="padding_eight_all bg-white">
                                    <form id="login_form" name="login_form">
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
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-heading btn-block hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display:none;">Reset password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pl-50">
                                <h6 class="mb-15">Password must:</h6>
                                <p>Be between 9 and 64 characters</p>
                                <p>Include at least tow of the following:</p>
                                <ol class="list-insider">
                                    <li>An uppercase character</li>
                                    <li>A lowercase character</li>
                                    <li>A number</li>
                                    <li>A special character</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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