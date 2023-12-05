@extends('frontend/layout/master')
@section('title')
Kwikcaart | Register
@endsection
@section('frontend/content')
        <section class="content-main mt-80 mb-80">
            <div class="container-fluid mx-auto"> 
                <div class="row">
                    <div class=" ">
                        <div class="card card-login mx-auto">
                            <div class="card-heading">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Create an Account</h4>
                                    <form method="POST" id="register_form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group my-2">
                                                    <input type="text" aria-required="true" size="30" value="" name="name" id="name" class="form-control " placeholder="Full Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group my-2">
                                                <input type="email" aria-required="true" size="30" value="" name="email" id="email" class="form-control " placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group my-2">
                                                <input type="password" aria-required="true" size="30" value="" name="password" id="password" class="form-control " placeholder="Enter Password">
                                            </div>
                                        </div>
                                        <!-- form-group// -->
                                        <div class="mb-3">
                                            <p class="small text-center text-muted">By signing up, you confirm that you’ve read and accepted our User Notice and Privacy Policy.</p>
                                        </div>
                                        <!-- form-group  .// -->
                                        <div class="mb-4 px-auto">
                                            <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                                                <i class="fas fa-spinner fa-spin" style="display:none"></i>Register</button>
                                        </div>
                                        <!-- form-group// -->
                                    </form>
                                    <p class="text-center small text-muted mb-15">or sign up with</p>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{route('google-auth')}}" class="p-3">
                                            <svg aria-hidden="true" class="icon-svg" style="vertical-align: bottom; margin-top: -4px" width="20" height="20" viewBox="0 0 20 20">
                                                <path d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 002.38-5.88c0-.57-.05-.66-.15-1.18z" fill="#4285F4"></path>
                                                <path d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 01-7.18-2.54H1.83v2.07A8 8 0 008.98 17z" fill="#34A853"></path>
                                                <path d="M4.5 10.52a4.8 4.8 0 010-3.04V5.41H1.83a8 8 0 000 7.18l2.67-2.07z" fill="#FBBC05"></path>
                                                <path d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 001.83 5.4L4.5 7.49a4.77 4.77 0 014.48-3.3z" fill="#EA4335"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ url('auth/facebook') }}" class="p-3">
                                            <svg aria-hidden="true" class="icon-svg" width="20" height="20" viewBox="0 0 20 20">
                                                <path d="M3 1a2 2 0 00-2 2v12c0 1.1.9 2 2 2h12a2 2 0 002-2V3a2 2 0 00-2-2H3zm6.55 16v-6.2H7.46V8.4h2.09V6.61c0-2.07 1.26-3.2 3.1-3.2.88 0 1.64.07 1.87.1v2.16h-1.29c-1 0-1.19.48-1.19 1.18V8.4h2.39l-.31 2.42h-2.08V17h-2.5z" fill="#4167B2"></path>
                                            </svg>
                                        </a>
                                        <a href="#" class="p-3 btnLoginSocialMobile d-none" id="signupWithMobileButton" data-toggle="modal" >
                                            <i class="fa-solid fa-mobile" style="color:DodgerBlue;"></i>&nbsp
                                            Phone
                                        </a>
                                    </div>
                                    <p class="text-center mb-2">Already have an account? <a href="/login">Sign in now</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

       
<div id="signupWithMobileModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="top: 20%;">
  
      <!-- Modal content-->
      <div class="modal-content " style="border-radius:20px;">
        <div class="modal-header text-center" style="padding-bottom:0px; border:none;">
            <h4 class="modal-title col-12">SIGN UP WITH MOBILE
            <a class="close" data-dismiss="modal">&times;</a>
            <br><span style="color:red; font-size:12px; display:none;" id="signUpWithMobileMsg">Code is in valid</span>
            </h4>
        </div>
        <div class="modal-body" style="padding-top:0px;">
        <form method="POST" action="{{ route('sendOtp') }}">
    @csrf   
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="contact" name="contact">
            </div>

            <button type="submit" class="btn btn-primary">Send OTP</button>
        </form>
        </div>
      </div>
  
    </div>
</div>

    @endsection
    @section('scripts')
    <script>
        $("#signupWithMobileButton").click(function() {
            $("#signupWithMobileModal").modal('show');
        });
    </script>
    <script>
        // Vanilla Javascript
        var input = document.querySelector(".phone");
        window.intlTelInput(input, ({
            // options here
        }));

        $(document).ready(function() {
            $('.iti__flag-container').click(function() {
                var countryCode = $('.iti__selected-flag').attr('title');
                var countryCode = countryCode.replace(/[^0-9]/g, '')
                $('.phone').val("");
                $('.phone').val("+" + countryCode + "" + $('.phone').val());
            });
        });
    </script>
    <script>
        $(document).on('submit', '#register_form', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/api/register",
                type: "post",
                data: new FormData(this),
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                    console.log(response)
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#register_form")[0].reset();

                       
                       
                            window.location.href = "/user/verification" + '/' + response[
                            "userId"];

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
    <script>
        $('#btnSubmitmobile').on('click', function() {
            $('#information').css('display', 'none')
            $('#verification').css('display', 'block')
        })
    </script>
@endsection