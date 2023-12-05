   @extends('frontend/layout/master')
   @section('title')
   Kwikcaart | Deals
   @endsection
   @section('frontend/content')

   <?php
    $today = \Carbon\Carbon::today();
    $deals = App\Models\Deals::where('status', 0)->where('start_date', '<=', $today)->where('end_date', '>', date('Y-m-d'))->get();
    ?>

@if(isset($deals) && sizeof($deals)>0)
    @include('frontend/deals')
    @endif
   @endsection

   @section('scripts')


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