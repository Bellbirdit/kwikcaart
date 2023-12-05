<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Code Verification</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/imgs/theme/favicon.svg')}}" />
    <!-- Template CSS -->
    <link href="{{asset('assets/css/main.css?v=1.1')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('build/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <main>
     




        <div class="container-fluid mt-5">
            <div class="main-container">
                <div class="row">
                    <div class="col-lg-12 text-center pt-3 pb-5">
                        <img src="{{asset('assets/imgs/theme/logo.png')}}" alt="Amor Rozgar Logo" style="height:80px" /> 
                    </div>
                </div>
                <div class="row m-t-mobile-50">
                    <div class="col-md-4 mx-auto">
                        <form id="form">
                            <div class="card width-100 m-t-b-50 shadow">
                                <div class="card-header  py-4" style="background-color:#000000; ">
                                    <h4 class="card-title text-center m-0" style="color:white  !important;">Kwikcaart Acccount Verification</h4>
                                </div>
                                <div class="card-body p-5">
                                    <div>
                                        @if(session()->has('invalid'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('invalid') }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center" name="dig1" id="digit1" type="text">
                                        </div>
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center" name="dig2" id="digit2" type="text">
                                        </div>
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center" name="dig3" id="digit3" type="text">
                                        </div>
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center" name="dig4" id="digit4" type="text">
                                        </div>
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center" name="dig5" id="digit5" type="text">
                                        </div>
                                        <div class="col-2 m-t-20">
                                            <input class="form-control text-center verifyAccountAjax" name="dig6" id="digit6" type="text">
                                        </div>
                                    </div>
                                    <?php
                                    $userId = Request::route('id');

                                    ?>
                                    <div class="row mt-4">
                                        <div class="col-12 text-center">
                                            <p>The verification code has been sent at "{{$user->email}}"</p>
                                            <p id="sendCodeAgain" class="new-on">Not received Email?
                                                <a href="javascript:;" id="btnResend" data-id="{{$user->id}}" class="c-cf">
                                                    <i class="fas fa-spinner fa-spin" style="display:none"></i> RESEND</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/additional-methods.min.js"></script>
    <script src="{{asset('assets/js/vendors/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/js/vendors/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/vendors/jquery.fullscreen.min.js')}}"></script>
    <!-- Main Script -->
    <script src="{{asset('assets/js/main.js?v=1.1')}}" type="text/javascript"></script>
    <script src="{{asset('build/js/intlTelInput.js')}}"></script>
    <script src="{{asset('assets/toastr/toastr.min.js')}}"></script>
    <link href="{{asset('assets/toastr/toastr.css')}}" rel="stylesheet">
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>


    <script>
        $(document).on('click', '#btnResend', function(e) {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/api/email/resend/',
                type: "post",
                dataType: "JSON",
                data: {
                    id: id
                },
                cache: false,
                beforeSend: function() {
                    $("#btnResend").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnResend").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
        $(document).on('keyup', '.verifyAccountAjax', function(e) {

            e.preventDefault();
            var digit1 = $("#digit1").val();
            var digit2 = $("#digit2").val();
            var digit3 = $("#digit3").val();
            var digit4 = $("#digit4").val();
            var digit5 = $("#digit5").val();
            var digit6 = $("#digit6").val();
            var verifyCode = digit1 + digit2 + digit3 + digit4 + digit5 + digit6;

            if (digit1 != "" && digit2 != "" && digit3 != "" && digit4 != "" && digit5 != "" && digit6 != "") {
                $.ajax({
                    type: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "verifyCode": verifyCode,
                    },
                    url: "/api/verify/{{$user->id}}",
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    success: function(data) {
                        console.log(data)
                        $(".loader").hide();
                        if (data.status == 'success') {
                            toastr.success('Success', data["msg"]);
                            const base = "{{url('/')}}";
                            window.location.replace(base);


                            // const base = new URL('/testsimsar/', location.href).href;
                            // window.location.replace(base+"user-profile");
                        }
                        if (data.status == 'fail') {
                            toastr.error('Failed', data["msg"])
                        }

                    },
                    error: function(res) {
                        $(".loader").hide();

                    }
                })
            } else {
                $.notify("Please enter your verification code", "error");
            }
        });

        $(document).on('keyup', '#digit1', function() {
            if ($(this).val().length == 1) {
                var input = document.getElementById("digit2").focus();
            }
        });

        $('#digit2').on('keyup', function() {

            if ($(this).val().length == 1) {
                var input = document.getElementById("digit3").focus();
            }
        });
        $('#digit3').on('keyup', function() {

            if ($(this).val().length == 1) {
                var input = document.getElementById("digit4").focus();
            }
        });
        $('#digit4').on('keyup', function() {

            if ($(this).val().length == 1) {
                var input = document.getElementById("digit5").focus();
            }
        });
        $('#digit5').on('keyup', function() {

            if ($(this).val().length == 1) {
                var input = document.getElementById("digit6").focus();
            }
        });



        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password .fass').addClass("fa-eye-slash");
                    $('#show_hide_password .fass').removeClass("fa-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password .fass').removeClass("fa-eye-slash");
                    $('#show_hide_password .fass').addClass("fa-eye");
                }
            });
            $('#form').validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: {
                    dig1: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                    dig2: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                    dig3: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                    dig4: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                    dig5: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                    dig6: {
                        required: true,
                        minlength: 1,
                        maxlength: 1,
                    },
                },

                messages: {

                    dig1: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                    dig2: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                    dig3: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                    dig4: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                    dig5: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                    dig6: {
                        required: 'Limit 1',
                        minlength: 'Limit 1',
                        maxlength: 'Limit 1',
                    },
                },

                invalidHandler: function(event, validator) { //display error alert on form submit

                },
                focusInvalid: function() {
                    // put focus on tinymce on submit validation
                    if (this.settings.focusInvalid) {
                        try {
                            var toFocus = $(this.findLastActive() || this.errorList.length && this
                                .errorList[0].element || []);
                            if (toFocus.is("textarea")) {
                                tinyMCE.get(toFocus.attr("id")).focus();
                            } else {
                                toFocus.filter(":visible").focus();
                            }
                        } catch (e) {
                            // ignore IE throwing errors when focusing hidden elements
                        }
                    }
                },

                errorPlacement: function(error, element) {
                    if (element.is(':checkbox')) {
                        error.insertAfter(element.closest(
                            ".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"
                        ));
                    } else if (element.is(':radio')) {
                        error.insertAfter(element.closest(
                            ".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                    } else if (element.hasClass('select2')) {
                        error.insertAfter(element.next('span'));
                    } else if (element.hasClass('textarea')) {
                        error.insertAfter(element.next('span'));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                highlight: function(element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass(
                            'has-error'); // set error class to the control group
                },

                unhighlight: function(element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass(
                            'has-error'); // set error class to the control group
                },

                success: function(label) {
                    label
                        .closest('.form-group').removeClass(
                            'has-error'); // set success class to the control group
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });



        });
    </script>
</body>

</html>