@if(Auth::check())
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:title" content="" />
        <meta property="og:type" content="" />
        <meta property="og:url" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta property="og:image" content="" />
        @include('layout/style')
        
    </head>
 
    <body>
        <div class="screen-overlay"></div>
        @include('layout/sidebar')
        <main class="main-wrap">
        @include('layout/header')
        
          @yield('content')
            <!-- content-main end// -->
        @include('layout/footer')
        </main>
        @include('layout/script')
        
        
        <script>
$(document).ready(function() {

    // notifications()
    $(function() {
        setInterval(notifications, 5000);
    });

    function notifications() {
        console.log('its calling')
        $.ajax({
            url: '/api/notification/manage',
            type: "get",
            dataType: "JSON",
            cache: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(response) {

                if (response["status"] == "fail") {
                    //toastr.error('Not Found',response["msg"])
                } else if (response["status"] == "success") {
                    $(".unreadCount").css('display', 'flex');
                    $(".unreadCount").html(' ')
                    if(response["unread"] >= 1){
                        $("#unread").css('display','block');
                    }else{
                        $("#unread").css('display','none');
                    }
                    $(".unreadCount").append(response["unread"])
                    $(".notification_head").html('')
                    $(".notification_head").append(response["head"])

                    $("#notification_data").html('')
                    $("#notification_data").prepend(response["messages"])

                    // setTimeout(notifications, 3000);
                }
            },
            error: function(error) {
                console.log(error)
            }
        });
    }
    // setTimeout(notifications, 1000);

});
</script>
        
        
    </body>
</html>
@endif
@if(!Auth::check())
 <script>window.location.href = "/login";</script>

 

@endif
