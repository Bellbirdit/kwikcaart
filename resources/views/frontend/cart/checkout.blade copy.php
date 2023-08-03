@extends('frontend/layout/master')
@section('title')
Safeer | Checkout
@endsection
@section('frontend/content')


<main class="main">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> Shop
                <span></span> Checkout
            </div>
        </div>
    </div>
    <div class="container mb-80 mt-50">
        <div class="row">
            <div class="col-lg-8 mb-40">
                <h1 class="heading-2 mb-10">Checkout</h1>
                <div class="d-flex justify-content-between">
                    @php
                     $counts= App\Models\Cart::where('user_id',auth()->user()->id)->count(); 
                    @endphp
                    <h6 class="text-body">There are <span
                   
                            class="text-brand">{{$counts }}</span> products in your cart</h6>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-7">
                <div class="row mb-50">
                    <div class="col-lg-6 mb-sm-15 mb-lg-0 mb-md-3">
                        @if(!Auth::check())
                            <div class="toggle_info">
                                <span><i class="fi-rs-user mr-10"></i><span class="text-muted font-lg">Already have an
                                        account?</span> <a href="#loginform" data-bs-toggle="collapse"
                                        class="collapsed font-lg login" aria-expanded="false">Click here to
                                        login</a></span>
                            </div>
                        @endif
                        <div class="panel-collapse collapse login_form" id="loginform">
                            <div class="panel-body">
                                <p class="mb-30 font-sm">If you have shopped with us before, please enter your
                                    details below. If you are a new customer, please proceed to the Billing &amp;
                                    Shipping section.</p>
                                <form id="login_form">
                                    <div class="form-group">
                                        <input type="text" required name="email" placeholder="Username Or Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" required name="password" placeholder="Password">
                                    </div>
                                    @if(!Auth::check())
                                    <div class="form-group">
                                        <button class="btn btn-md" name="login" id="btnSubmit"> <i
                                                class="fa fa-spinner fa-spin fa-pulse" style="display:none"></i> Log
                                            in</button>
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                @php
                 $carts= App\Models\Cart::where('user_id',auth()->user()->id)->get();
                  @endphp

                @if(isset($carts) && sizeof($carts) > 0 )
                    <form action="{{ route('place.order') }}" method="post" class="require-validation"
                        data-cc-on-file="false"
                        data-stripe-publishable-key="pk_test_51Jeb4lCFc8UmysBwfagraSa8TOIkgttTyyfkpVSJYPTkm3vQaCvTX2zTqvqOP3UwW7FsTP4BLziCGRCrQzv0HLTa006KslFgjF"
                        id="payment-form">


                        @csrf
                        <div class="row">
                            <h4 class="mb-30">Billing Details</h4>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <input type="text" required="" class="first_name"
                                        value="@if(Auth::check()){{ Auth::user()->name }}@endif" name="first_name"
                                        placeholder="Enter your full name *">
                                </div>
                                <div class="form-group col-lg-6">
                                    <input type="text" name="address" id="ship-address"
                                        value="@if(Auth::check()){{ Auth::user()->address }}@endif" class="address"
                                        required="" placeholder="Address *">
                                </div>
                            </div>


                            <div class="row">

                                <div class="form-group col-lg-6">
                                    <input required="" type="number"
                                        value="@if(Auth::check()){{ Auth::user()->contact }}@endif" name="phone"
                                        class="phone" placeholder="Phone *">
                                </div>
                                <div class="form-group col-lg-6">
                                    <input required="" type="email"
                                        value="@if(Auth::check()){{ Auth::user()->email }}@endif" name="email"
                                        class="email" placeholder="Email address *">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <select name="delivery_option" class="form-control delivery_option"
                                        id="deliveryOptions" required>
                                        <option value="express_delivery">Express Delivery</option>
                                        <option value="standerd_delivery">Standerd Delivery</option>
                                        <option value="self_pickup">Self Pick Up From Store</option>
                                    </select>
                                </div>
                                <?php $shippingschedule = App\Models\StoreShippingSchedule::where('store_id',Session::get('store_id'))->latest()->get(); ?>

                                <div class="row" id="sdlivery" style="display: none">
                                    @foreach($shippingschedule as $scheduale)

                                        <h5 class="py-2"> Date : {{ $scheduale->date }}</h5>
                                        <hr>

                                        <?php $shippings = App\Models\ShippingTime::where('date_id',$scheduale->id)->get(); ?>

                                        @foreach($shippings as $shipping)

                                            <div class="col-lg-3">
                                                <article class="box mb-3 bg-light text-center">

                                                    <div class="custome-radio">
                                                        <input type="hidden" name="pick_date"
                                                            value="{{ $scheduale->date }}">
                                                        <input class="form-check-input " required="" type="radio"
                                                            name="pick_time" id="exampleRadiosss{{ $shipping->id }}"
                                                            date="{{ $scheduale->date }}"
                                                            value="{{ $shipping->start_time }} To {{ $shipping->end_time }}">
                                                        <label class="form-check-label"
                                                            for="exampleRadiosss{{ $shipping->id }}"
                                                            data-bs-toggle="collapse" data-target="#checkPayment"
                                                            aria-controls="checkPayment">{{ $shipping->start_time }}
                                                            To {{ $shipping->end_time }}</label>
                                                    </div>


                                                </article>
                                            </div>
                                        @endforeach
                                    @endforeach


                                </div>

                                <?php $pickupchedule = App\Models\PickupSchedule::where('store_id',Session::get('store_id'))->latest()->get(); ?>

                                <div class="row" id="sPickup" style="display: none">
                                    @foreach($pickupchedule as $pickschedule)

                                        <h5 class="py-2"> Date : {{ $pickschedule->date }}</h5>
                                        <hr>

                                        <?php $pickdate = App\Models\PickupTime::where('date_id',$pickschedule->id)->get(); ?>

                                        @foreach($pickdate as $pickup)

                                            <div class="col-lg-3">
                                                <article class="box mb-3 bg-light text-center">

                                                    <div class="custome-radio">
                                                        <input type="hidden" name="pick_date"
                                                            value="{{ $pickschedule->date }}">
                                                        <input class="form-check-input " required="" type="radio"
                                                            name="pick_time" id="exampleRadioss{{ $pickup->id }}"
                                                            date="{{ $pickschedule->date }}"
                                                            value="{{ $pickup->start_time }} To {{ $pickup->end_time }}">
                                                        <label class="form-check-label"
                                                            for="exampleRadioss{{ $pickup->id }}"
                                                            data-bs-toggle="collapse" data-target="#checkPayment"
                                                            aria-controls="checkPayment">{{ $pickup->start_time }} To
                                                            {{ $pickup->end_time }}</label>
                                                    </div>


                                                </article>
                                            </div>
                                        @endforeach
                                    @endforeach


                                </div>

                                <div class="form-group col-lg-6">
                                    <input type="password" class="password" name="password"
                                        placeholder="Enter Password*">
                                </div>
                            </div>
                            <div class="form-group mb-30">
                                <textarea rows="5" class="additional_information" name="additional_information"
                                    placeholder="Additional information"></textarea>
                            </div>


                        </div>
            </div>
            <div class="col-lg-5">
                <div class="border p-40 cart-totals ml-30 mb-50">
                    <div class="d-flex align-items-end justify-content-between mb-30">
                        <h4>Your Order</h4>
                        <h6 class="text-muted">Subtotal</h6>
                    </div>
                    <div class="divider-2 mb-30"></div>
                    <div class="table-responsive order_table checkout">
                        <table class="table no-border">
                            <tbody>
                                
                                @foreach($carts as $cart)
                               
                                    <tr>
                                        <td class="image product-thumbnail"> <img src="{{asset('uploads/files/'.$cart->image)}}" alt="#">
                                        </td>
                                        <td>
                                            <h6 class="w-160 mb-5"><a href="shop-product-full.html"
                                                    class="text-heading">{{ $cart->name }}</a></h6></span>
                                            <div class="product-rate-cover">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" style="width:90%">
                                                    </div>
                                                </div>
                                                <span class="font-small ml-5 text-muted"> (4.0)</span>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="text-muted pl-20 pr-20">x {{ $cart->quantity }}</h6>
                                        </td>
                                        <td>
                                            <h4 class="text-brand">{{ $cart->price * $cart->quantity }}</h4>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="total text-muted">
                            @php
                            $grandtotal = session::get('grandtotal');
                            @endphp
                            <h6>
                                Total Price: AED {{$grandtotal}}
                            </h6>
                        </div>


                    </div>

                </div>
                <div class="row">

                </div>
                <div class="payment ml-30">
                    <h4 class="mb-30">Payment</h4>
                    <div class="payment_option">
                        <div class="custome-radio">
                            <input type="hidden" name="" class="" id="is_stripe_yes" value="">
                            <input class="form-check-input payment_online" required="" type="radio"
                                name="payment_option" id="exampleRadios4">
                            <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse"
                                data-target="#checkPayment" aria-controls="checkPayment">Online payment</label>
                        </div>
                        <div class="custome-radio">
                            <input type="hidden" name="" class="" id="is_stripe_no" value="">
                            <input class="form-check-input payment_online payment_option" required="" type="radio"
                                name="payment_option" id="exampleRadios5" value="no">

                            <label class="form-check-label" for="exampleRadios5" data-bs-toggle="collapse"
                                data-target="#paypal" aria-controls="paypal">Cash on delivery</label>
                        </div>
                        @if(Auth::check())
                            <?php  $wallet = App\Models\Wallet::where('user_id',Auth::id())->pluck('amount')->first();
                                        if(isset($wallet))
                                        {
                                        ?>
                            <div class="custome-radio">

                                <input class="form-check-input payment_online payment_option" required="" type="radio"
                                    name="payment_option" id="exampleRadios6" value="wallet">

                                <label class="form-check-label" for="exampleRadios6" data-bs-toggle="collapse"
                                    data-target="#paypal" aria-controls="paypal">Wallet ({{ $wallet }} )</label>
                            </div>
                            <?php } ?>
                        @endif
                        <div id="payment_field_show" style="display:none" class="form-group create-account">
                            <div class="row">
                                <!-- <h3 class="color-white cart-details-title">Credit Card</h3> -->
                                <div class="checkout-form">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group mb-20">
                                                    <div class="input-group">
                                                        <input type="text" name="holder_name" class="form-control"
                                                            placeholder="Name on card*" autocomplete="off" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group mb-20">
                                                    <div class="input-group">
                                                        <input type="text" name="cardno"
                                                            class="form-control card-number" placeholder="Card Number*"
                                                            autocomplete="off" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-20">
                                                    <div class="input-group">
                                                        <input type="text" name="month"
                                                            class="form-control card-expiry-month" placeholder='MM'
                                                            size='2' />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group mb-20">
                                                    <div class="input-group">
                                                        <input type="text" name="year"
                                                            class="form-control card-expiry-year" placeholder='YYYY'
                                                            size='4' />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group mb-20">
                                                    <div class="input-group">
                                                        <input type="text" name="cvc" class="form-control card-cvc"
                                                            placeholder="CVC" size='4' autocomplete="off" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="alert error text-danger fw-bold"></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payment-logo d-flex">
                        <!-- <img class="mr-15" src="assets/imgs/theme/icons/payment-paypal.svg" alt=""> -->
                        <img class="mr-15" src="assets/imgs/theme/icons/payment-visa.svg" alt="">
                        <img class="mr-15" src="assets/imgs/theme/icons/payment-master.svg" alt="">
                        <!-- <img src="assets/imgs/theme/icons/payment-zapper.svg" alt=""> -->
                    </div>
                    <button type="submit" class="btn btn-fill-out btn-block mt-30 submit_form" id="submit_form"> <i
                            class="fa fa-spinner fa-spins fa-pulse " style="display:none"> </i> Place an Order <i
                            class="fi-rs-sign-out ml-15"></i></button>
                </div>
            </div>

            </form>


            @endif
        </div>
    </div>
</main>
@endsection
@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script>
    $("#deliveryOptions").change(function () {
        var delivery_option = $(this).val();

        if (delivery_option == "standerd_delivery") {

            $("#sdlivery").css('display', 'flex');
            $("#sPickup").css('display', 'none');
        } else if (delivery_option == "self_pickup") {


            $("#sPickup").css('display', 'flex');
            $("#sdlivery").css('display', 'none');

        } else {
            $("#sdlivery").css('display', 'none');
            $("#sPickup").css('display', 'none');

        }
    })

</script>
<script type="text/javascript">
    $(function () {
        $("#payment-method").on('change', function () {
            if ($(this).is(':checked')) {
                $('.pay-with-stripe').slideDown('slow');
            } else {
                $('.pay-with-stripe').hide('slow');
            }
        });

        $(".place_order").on('click', function () {

            toastr.info('Please Login to Place Order')

            $("#loginform").addClass('show')

        });
    });

    $(document).ready(function () {
        $('.card-number').inputmask('9999-9999-9999-9999');
        $('.card-expiry-month').inputmask('99');
        $('.card-expiry-year').inputmask('9999');
        $('.card-cvc').inputmask('999');


    });

</script>


<!-- stripe payment -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script>
    $(function () {
        $(document).on("change", '.payment_online', function () {

            if ($(this).val() == "no") {
                document.getElementById('is_stripe_no').value = "no";
                $("#payment_field_show").hide();
                $('.submit_form').attr('id');


            } else if ($(this).val() == "wallet") {
                document.getElementById('is_stripe_no').value = "no";
                $("#payment_field_show").hide();
                $('.submit_form').attr('id');
            } else {
                document.getElementById('is_stripe_yes').value = "yes";
                $('.submit_form').removeAttr('id');
                $("#payment_field_show").css('display', 'flex');



            }
        })
    });

</script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script>
    let autocomplete;
    let address1Field;
    let address2Field;
    let postalField;

    function initAutocomplete() {
        address1Field = document.querySelector("#ship-address");

        address2Field = document.querySelector("#address2");
        postalField = document.querySelector("#postcode");

        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            componentRestrictions: {
                country: ["AE"]
            },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        address1Field.focus();
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            // @ts-ignore remove once typings fixed
            const componentType = component.types[0];

            switch (componentType) {
                case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    break;
                }

                case "route": {
                    address1 += component.long_name;
                    break;
                }

                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                }
                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }

                case "administrative_area_level_1": {
                    document.querySelector("#map_state").value = component.long_name;
                    setState()
                    break;
                }

                case "locality":
                    document.querySelector("#locality").value = component.long_name;
                    break;

                case "country":
                    // document.querySelector("#country").value = component.short_name;
                    $("#country").val(component.short_name)
                    break;


            }
        }

        address1Field.value = address1;
        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        // address2Field.focus();
    }
    window.initAutocomplete = initAutocomplete;

</script>
<!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDLgkbCBN1poChfbWCl7i3yIK_mFI8ORyU&callback=initAutocomplete&libraries=places&v=weekly"
    defer></script> -->
<script type="text/javascript">
    $(function () {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function (e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');

            $('.has-error').removeClass('has-error');
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error').text(response.error.message);
            } else {
                // token contains id, last4, and card type
                var token = response['id'];
                // insert the token into the form so it gets submitted to the server
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });
    $(document).on('click', '#exampleRadios4', function (e) {

        console.log('remove')
        $('.submit_form').removeAttr('id');

    });

    $(document).on('click', '#exampleRadios6', function (e) {

        $('.submit_form').attr('id', 'submit_form');

    });

    $(document).on('click', '#exampleRadios5', function (e) {
        console.log('add')
        $('.submit_form').attr('id', 'submit_form');
    });


    $(document).on('click', '#submit_form', function (e) {
        var payment_option = $("input[name='payment_option']:checked").val()

        var pick_time = $("input[name='pick_time']:checked").val();
        var pick_date = $("input[name='pick_time']:checked").attr('date');



        if (payment_option == 'no' || payment_option == 'wallet') {

            e.preventDefault();
            var first_name = $('.first_name').val();

            var address = $('.address').val();
            var phone = $('.phone').val();
            var email = $('.email').val();
            var delivery_option = $('.delivery_option').val();
            var additional_information = $('.additional_information').text();


            $.ajax({
                url: "/place/order",
                type: "post",
                data: {
                    first_name: first_name,
                    address: address,
                    phone: phone,
                    email: email,
                    delivery_option: delivery_option,
                    additional_information: additional_information,
                    payment_option: payment_option,
                    pick_date: pick_date,
                    pick_time: pick_time
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                beforeSend: function () {
                    $("#submit_form").attr('disabled', true);
                    $(".fa-spins").css('display', 'inline-block');
                },
                complete: function () {
                    $("#submit_form").attr('disabled', false);
                    $(".fa-spins").css('display', 'none');
                },
                success: function (response) {
                    console.log(response)
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                        window.location.href = "/success";

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });



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
                    location.reload()
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

</script>
@endsection
