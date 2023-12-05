@extends('frontend/layout/master')
@section('title')
Kwikcaart | Home
@endsection
@section('frontend/content')

<?php
$today = \Carbon\Carbon::today();
$storedeals = App\Models\StorewiseDeal::where('store_id', Session::get('store_id'))->where('status', 0)->where('featured', 0)->where('start_date', '<=', $today)->where('end_date', '>', date('Y-m-d'))->get();
?>

@if(isset($storedeals) && sizeof($storedeals)>0)
    @include('frontend/deals')
    @endif

@endsection
@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=Function.prototype" async>
</script>
<script>
    $(document).on('click', '.add_cart', function() {
        $(".append_cart").html('')
        var id = $(this).attr('id');
        var qty = $(".qty_value").val();
        $.ajax({
            url: "/add/cart/" + id,
            type: "get",
            data: {
                qty: qty
            },
            dataType: "JSON",
            cache: false,
            success: function(response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                    window.location.href = "/login";
                } else if (response["status"] == "success") {
                    $(".append_cart").html(response['html'])
                    toastr.success('Success', response["msg"])
                    $(".total").html(response['total'])
                    $('.cart_count').html(response['cart_count'])
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
@endsection