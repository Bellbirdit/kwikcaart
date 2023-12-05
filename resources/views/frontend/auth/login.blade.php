@extends('frontend/layout/master')
@section('title')
Kwikcaart | Login
@endsection
@section('frontend/content')
<style>
    #password-toggle {
    border: none;
    background: transparent;
}
</style>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
        <section class="content-main mt-80 mb-80">
            <div class="card mx-auto card-login">
                <div class="card-body">
                    <h4 class="card-title mb-4">Sign in</h4>
                    @if(session('login_error'))
                    <div class="alert alert-danger">
                        <p>{{session('login_error')}}</p>
                        {{session()->forget('login_error')}}
                    </div>
                    @endif
                    <form method="POST" id="login_form">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control" placeholder="email" name="email" type="text" />
                        </div>
                        <!-- form-group// -->
                       <div class="input-group mb-3">
                            <input class="form-control" placeholder="Password" id="password-input" type="password" name="password" />
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" style="height: 100%;background: black;" type="button" id="password-toggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <!-- form-group// -->
                        <div class="mb-3">
                            <a href="forgot/password" class="float-end font-sm text-muted">Forgot password?</a>
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" checked="" />
                                <span class="form-check-label">Remember</span>
                            </label>
                        </div>
                        <!-- form-group form-check .// -->
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary w-100" id="btnSumbit"><i
                                    class="fas fa-spinner fa-spin" style="display:none"></i> Login
                            </button>
                        </div>
                        <!-- form-group// -->
                    </form>
                    <p class="text-center small text-muted mb-15">or sign up with</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{route('google-auth')}}" class="p-3">
                            <svg aria-hidden="true" class="icon-svg" width="30" height="30" viewBox="0 0 20 20">
                                <path
                                    d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 002.38-5.88c0-.57-.05-.66-.15-1.18z"
                                    fill="#4285F4"></path>
                                <path
                                    d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 01-7.18-2.54H1.83v2.07A8 8 0 008.98 17z"
                                    fill="#34A853"></path>
                                <path d="M4.5 10.52a4.8 4.8 0 010-3.04V5.41H1.83a8 8 0 000 7.18l2.67-2.07z"
                                    fill="#FBBC05"></path>
                                <path
                                    d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 001.83 5.4L4.5 7.49a4.77 4.77 0 014.48-3.3z"
                                    fill="#EA4335"></path>
                            </svg>
                        </a>
                        <a href="javascript:;" class="p-3">
                            <svg aria-hidden="true" class="icon-svg" width="30" height="30" viewBox="0 0 20 20">
                                <path
                                    d="M3 1a2 2 0 00-2 2v12c0 1.1.9 2 2 2h12a2 2 0 002-2V3a2 2 0 00-2-2H3zm6.55 16v-6.2H7.46V8.4h2.09V6.61c0-2.07 1.26-3.2 3.1-3.2.88 0 1.64.07 1.87.1v2.16h-1.29c-1 0-1.19.48-1.19 1.18V8.4h2.39l-.31 2.42h-2.08V17h-2.5z"
                                    fill="#4167B2"></path>
                            </svg>
                        </a>
                    </div>
                    <p class="text-center mb-4">Don't have account? <a href="/register">Sign up</a></p>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
@section('scripts')
<script>
   // JavaScript code to toggle password visibility
$(document).ready(function () {
    $('#password-toggle').on('click', function () {
        var passwordInput = $('#password-input');
        var passwordToggle = $(this);

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            passwordToggle.find('i').removeClass('fas fa-eye').addClass('fas fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            passwordToggle.find('i').removeClass('fas fa-eye-slash').addClass('fas fa-eye');
        }
    });
});

</script>
<script>
    $(document).on('submit', '#login_form', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/api/login",
            type: "post",
            data: new FormData(this),
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                console.log(response)
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                    $("#login_form")[0].reset();
                    if (response["user"]["type"] == "3") {
                        if (response["user"]["status"] == "active") {
                            window.location.href = "/";
                        }


                    } else if (response["user"]["type"] == "2") {
                        if (response["user"]["status"] == "active") {
                            window.location.href = "/dashboard";
                        }

                    } else if (response["user"]["type"] == "1") {
                        if (response["user"]["status"] == "active") {
                            window.location.href = "/dashboard";
                        }

                    } else {

                        window.location.href = "/";

                    }

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

</script>

@endsection
