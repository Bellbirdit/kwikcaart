<script src="{{asset('assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    

<script src="{{asset('assets/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
    <script src="{{asset('assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/slick.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.syotimer.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/waypoints.js')}}"></script>
    <script src="{{asset('assets/js/plugins/wow.js')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/js/plugins/magnific-popup.js')}}"></script>
    <script src="{{asset('assets/js/plugins/select2.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/counterup.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/images-loaded.js')}}"></script>
    <script src="{{asset('assets/js/plugins/isotope.js')}}"></script>
    <script src="{{asset('assets/js/plugins/scrollup.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.vticker-min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.theia.sticky.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery.elevatezoom.js')}}"></script>
    <!-- Template  JS -->
    <script src="{{asset('assets/js/main.js?v=5.6')}}"></script>
    <script src="{{asset('assets/js/shop.js?v=5.6')}}"></script>
    <!-- new code -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

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
    <?php $store_id  = Session::get('store_id'); ?>

    <script>
        
    $( document ).ready(function() {

var session = "{{$store_id}}";
 console.log(session);
 if(session == '')
 {


   $(window).on("load", function () {
       $("#preloader-active").delay(450).fadeOut("slow");
       $("body").delay(450).css({
           overflow: "visible"
       });
       $("#onloadModal").modal("show");
   });
}
else{


   $(window).on("load", function () {
    $("#preloader-active").delay(450).fadeOut("slow");
       $("body").delay(450).css({
           overflow: "visible"
       });
       $("#onloadModal").modal("hide");

   });
}
});

$(document).on('click', '.remove', function () {
   var id = $(this).attr('id');
   $.ajax({
            url: "/cart/remove/"+id,
            type: "get",
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    $("#remove"+id).remove();
                    toastr.success('Success',response["msg"])
                    $('.cart_count').html(response['cart_count'])
                    $(".total").html(response['total'])
                    
                }
            },
            error: function (error) {
                console.log(error);
            }
        });




});


$(document).on('click', '.collapserole', function () {
 
       var id = $(this).attr('id');
    // $('.collapse').addClass('show')
    $('.collapse'+id).toggle('show')
    //  $('.collapse').removeClass('show')
     


});




    </script>




<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
    @yield('scripts')
    