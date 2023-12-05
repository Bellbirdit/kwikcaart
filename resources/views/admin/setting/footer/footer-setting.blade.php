@extends('layout/master')
@section('title')
Kwikcaart | Footer Setting
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Site settings</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row gx-5">
                <div class="col-lg-12">
                    <section class="content-body p-xl-4">
                        <form id="footersetting_form">
                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input class="form-control" type="text" name="phone" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Info Email </label>
                                        <input class="form-control" type="text" name="info_email" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Career Email </label>
                                        <input class="form-control" type="text" name="career_email" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">head office address</label>
                                        <textarea type="text" name="address"class="form-control"></textarea>
                                    </div>
                                </div>
                                <!-- col.// -->
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label class="form-label">Facebook </label>
                                        <input class="form-control" type="text" name="facebook" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Instagram </label>
                                        <input class="form-control" type="text" name="instagram" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Linked In </label>
                                        <input class="form-control" type="text" name="linkedin" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"> Twitter </label>
                                        <input class="form-control" type="text" name="twitter" placeholder="Type here" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label"> Youtube </label>
                                        <input class="form-control" type="text" name="youtube" placeholder="Type here" />
                                    </div>
                                </div>
                                <!-- col.// -->
                            </div>
                            <!-- row.// -->
                            <!-- <div class="row border-bottom mb-4 pb-4">
                                <div class="col-md-5">
                                    <h5>Android App link</h5>
                                    <p class="text-muted" style="max-width: 90%">Lorem ipsum dolor sit amet, consectetur
                                        adipisicing something about this</p>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="" id="mycheck_notify"
                                            checked />
                                        <label class="form-check-label" for="mycheck_notify"> Send notification on each
                                            user registration </label>
                                    </div>
                                    <div class="mb-3">
                                        <input class="form-control" placeholder="link" />
                                    </div>
                                </div>
                              
                            </div> -->
                            <!-- <div class="row border-bottom mb-4 pb-4">
                                <div class="col-md-5">
                                    <h5>IOS App link</h5>
                                    <p class="text-muted" style="max-width: 90%">Lorem ipsum dolor sit amet, consectetur
                                        adipisicing something about this</p>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="" id="mycheck_notify"
                                            checked />
                                        <label class="form-check-label" for="mycheck_notify"> Send notification on each
                                            user registration </label>
                                    </div>
                                    <div class="mb-3">
                                        <input class="form-control" placeholder="link" />
                                    </div>
                                </div>
                               
                            </div> -->
                            <!-- row.// -->
                            <!-- row.// -->
                            <div class="row border-bottom mb-4 pb-4">
                                <div class="col-md-5">
                                    <h5>Contact Us</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="contact_us" placeholder="link" />
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <h5>About Us</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="about_us" placeholder="link" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Privacy Policy</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="privacy_policy" placeholder="link" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Return Policy</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="return_policy" placeholder="link" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Terms & Conditions</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="terms" placeholder="link" />
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <h5>Support 24/7</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="support" placeholder="link" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Express Delivery</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="express_delivery" placeholder="link" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Order & Collect</h5>
                                </div>
                                 <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="order_collect" placeholder="link" />
                                    </div>
                                    <div class="col-md-5">
                                    <h5>Home Delivery</h5>
                                </div>
                                </div>
                                <div class="col-md-5">
                                    <h5>Home Delivery</h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="home_delivery" placeholder="link" />
                                    </div>
                                </div> 
                                 <div class="col-md-5">
                                    <h5>
                                        Shipping Details
                                    </h5>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="shipping_details" placeholder="link" />
                                    </div>
                                </div>
                                 <div class="col-md-5">
                                    <h5>Safeer Plus</h5>
                                </div>
                                 <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="safeer_plus" placeholder="link" />
                                    </div>
                                </div>
                                 <div class="col-md-5">
                                    <h5>Faqs</h5>
                                </div>
                                 <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="faq" placeholder="link" />
                                    </div>
                                </div>
                                 <div class="col-md-5">
                                    <h5>Our Stores</h5>
                                </div>
                                 <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="our_stores" placeholder="link" />
                                    </div>
                                </div>
                                 <div class="col-md-5">
                                    <h5>Service & Warrenty</h5>
                                </div>
                                 <div class="col-md-7">
                                    <div class="mb-3">
                                        <input class="form-control" name="service_warrenty" placeholder="link" />
                                    </div>
                                </div>
                                
                            </div>

                         
                            <!-- <div class="row border-bottom mb-4 pb-4">
                                <div class="col-md-12">
                                    <h5>Useful Links</h5>
                                    <p class="text-muted" style="max-width: 90%">Lorem ipsum dolor sit amet, consectetur
                                        adipisicing something about this</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>menu one</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="link" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>menu two</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="link" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>menu three</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="link" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>menu four</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="link" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>menu five</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <input class="form-control" placeholder="link" />
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-spinner fa-spin"
                                style="display: none;"></i> Save all change</button>
                            &nbsp;
                            <!-- <button class="btn btn-light rounded font-md" type="reset">Reset</button> -->
                        </form>
                    </section>
                    <!-- content-body .// -->
                </div>
                <!-- col.// -->
            </div>
            <!-- row.// -->
        </div>
        <!-- card-body .//end -->
    </div>
    <!-- card .//end -->
</section>

@endsection
@section('scripts')
<script>
    $(document).ready(function (e) {

        $("#footersetting_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/footersetting/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
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
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>

@endsection
