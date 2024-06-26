<div class="tab-pane fade" id="track-orders" role="tabpanel" aria-labelledby="track-orders-tab">
    <div class="card">
        <div class="card-header">
            <h1 class="mb-3">Orders tracking</h1>
        </div>
        <div class="card-body contact-from-area">
            <p>To track your order please enter your OrderID in the box below and press "Track" button. This was given
                to
                you on your receipt and in the confirmation email you should have received.</p>
            <div class="row">
                <div class="col-lg-8">
                    <form class="contact-form-style mt-30 mb-50" action="#" method="post">
                        <div class="input-style mb-20">
                            <label>Order ID</label>
                            <input name="order_number" class="order_number"
                                placeholder="Found in your order confirmation email" type="text" />
                        </div>
                        <!-- <div class="input-style mb-20">
                        <label>Billing email</label>
                        <input name="billing-email" placeholder="Email you used during checkout" type="email" />
                    </div> -->
                        <div class="mb-3 " id="append_status">
                            <p class="append_status"></p>
                        </div>
                        <span class="track-order btn btn-success text-white">Track</span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.track-order', function (e) {
        var order_number =  $('.order_number').val();
        // alert(order_number)
        e.preventDefault();
        $.ajax({
            url: "/api/order/tracking",
            type: "post",
            data: {
                order_number: order_number
            },
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            cache: false,
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                console.log(response)
                if (response["status"] == "fail") {
                    // $('#exampleModal').modal('hide')
                    toastr.error('Failed',response["msg"])
                 
                } else if (response["status"] == "success") {
                    // toastr.success('Success',response["msg"])
                     if(response['html'] == "")
                     {
                        $('#append_status').css('display','none');
                     }else{
                        $('#append_status').css('display','block');

                        $('.append_status').html(response["html"])


                     }

                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
</script>