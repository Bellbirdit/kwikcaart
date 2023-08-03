

@extends('frontend/layout/master')
@section('title')
Safeer | Forgot Password
@endsection
@section('frontend/content')
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{asset('assets/img/loader.svg')}}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    <div class="cover-image" data-image-src="{{asset('assets/img/backgrounds/1.jpg')}}">
        <div class="page">
            <div class="page-single">
                <div class="container">
                    <div class="row">
                    <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 col-xs-10 card-sigin-main py-4 justify-content-center mx-auto">
                            <div class="card-sigin">
                                <!-- Demo content-->
                                <div class="main-card-signin d-md-flex">
                                    <div class="wd-100p">
                                        <div class="mb-3  text-center" > <a href="/"><img src="{{asset('assets/logo/logo.png')}}" class="sign-favicon ht-40 " alt="logo" style="height:80px;"></a></div>
                                        <div class="  mb-1">
                                            <div class="main-signin-header">
                                                <div class="text-center">
                                                    <h2>Welcome back!</h2>
                                                    <h4 class="text-center">Reset Your Password</h4>
                                                    <form id="reset_password" name="reset_password">
                                                        <div class="row">
                                                    
                                                                <div class="form-group text-start">
                                                                    <input type="hidden" name='token' value="{{$token}}">
                                                                    <label class="form-label">New Password</label>
                                                                    <input type="password" name="password" class="form-control" required placeholder="Enter new password" />
                                                                </div>
                                                                <div class="form-group text-start">
                                                                    <label class="form-label">Confirm Password</label>
                                                                    <input type="password" name="password_confirmation" required class="form-control" placeholder="Confirm password" />
                                                                </div>

                                                                <div class="d-flex justify-content-between align-items-center m-0 float-right">

                                                                    <button href="javascript:;" type="submit" class="btn ripple btn-primary btn-block" id="btnSubmit">
                                                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>Change Password</button>
                                                                </div>
                                                        
                                                        </div>

                                                    </form>
                                                    <!-- <div class="mt-2 d-flex text-center justify-content-center">
																<a href="https://www.facebook.com/" target="_blank" class="btn btn-icon btn-facebook me-3" type="button">
																	<span class="btn-inner--icon"> <i class="bx bxl-facebook tx-18 tx-prime"></i> </span>
																</a>
																<a href="https://www.twitter.com/" target="_blank" class="btn btn-icon me-3" type="button">
																	<span class="btn-inner--icon"> <i class="bx bxl-twitter tx-18 tx-prime"></i> </span>
																</a>
																<a href="https://www.linkedin.com/" target="_blank" class="btn btn-icon me-3" type="button">
																	<span class="btn-inner--icon"> <i class="bx bxl-linkedin tx-18 tx-prime"></i> </span>
																</a>
																<a href="https://www.instagram.com/" target="_blank" class="btn  btn-icon me-3" type="button">
																	<span class="btn-inner--icon"> <i class="bx bxl-instagram tx-18 tx-prime"></i> </span>
																</a>
															 </div> -->
                                                </div>
                                            </div>
                                            <div class="main-signup-footer mg-t-20 text-center">
                                                <p>Already have an account? <a href="/signin">Sign In</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JQuery min js -->
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


