@extends('frontend/layout/master')
@section('title')
Kwikcaart | Forgot Password
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
    <div class="page-content pt-50 pb-150">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-12 m-auto" style="padding:30px; background:#fff; border:2px solid #ccc">
                    <div class="login_wrap widget-taber-content background-white">
                        <div class="padding_eight_all bg-white">
                            <div class="heading_s1">
                                <img class="border-radius-15" src="assets/imgs/page/forgot_password.svg" alt="" />
                                <h2 class="mb-15" style="font-size:18px">Forgot your password?</h2>
                                <p class="mb-30">Not to worry, we got you! Let’s get you a new password. Please enter
                                    your email address or your Username.</p>
                            </div>
                            <form action="" id="forget_password" name="forget_password">
                                <h4>
                                    <p id="emailLabel" style="padding-bottom:15px">Please Enter Your Email </p>
                                </h4>

                                <div class="form-group">
                                    <div class="alert alert-dark-success alert-dismissible fade show" id="alertLabel"
                                        style="display:none">

                                        We have emailed your password reset link, please checkout your mailbox!
                                    </div>
                                    <input type="email" id="email" name="email" required class="form-control"
                                        placeholder="Enter your email-address">
                                </div>
                                <!-- <div class="login_footer form-group">
                                            <div class="chek-form">
                                                <input type="text" required="" name="email" placeholder="Security code *" />
                                            </div>
                                            <span class="security-code">
                                                <b class="text-new">8</b>
                                                <b class="text-hot">6</b>
                                                <b class="text-sale">7</b>
                                                <b class="text-best">5</b>
                                            </span>
                                        </div> -->
                                <div class="login_footer form-group mb-50">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="checkbox"
                                                id="exampleCheckbox1" value="" />
                                            <label class="form-check-label" for="exampleCheckbox1"><span>I agree to
                                                    terms & Policy.</span></label>
                                        </div>
                                    </div>
                                    <a class="text-muted" href="#">Learn more</a>
                                </div>
                                <div class="form-group">
                                <button href="javascript:;" type="submit" class="btn btn-primary " id="btnSubmit">
                                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i> Send password reset email</button>
                                </div>
                            </form>
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
        $(document).ready(function() {
            $(document).on('click', '#btnSubmit', function() {
                $("#forget_password").submit();

            })
            $(document).on('submit', '#forget_password', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/api/forget-password',
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
                            $("#email").css('display', 'none')
                            $("#btnSubmit").css('display', 'none')
                            $("#emailLabel").css('display', 'none')
                            $("#alertLabel").css('display', 'block')

                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection
