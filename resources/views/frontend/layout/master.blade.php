

<!DOCTYPE html>
<html class="no-js" lang="en">

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

    @include('frontend/layout/style')
      <style>
* {box-sizing: border-box}
body {font-family: "Lato", sans-serif;}

/* Style the tab */
.tab {
  float: left;
  width: 20%;
  max-height:380px;
  overflow: scroll;
}
.tab a {
  display: block;
   width: 100%;
     color: #000;
    font-weight: 400;
}
.tab a:hover {
    background-color: #1770151f;
    border-radius: 10px;
}
.tab a.active {
    background-color: #1770151f;
    border-radius: 10px;
}
.tabcontent {
    width: 80%;
  display: none;
  max-height:380px;
  overflow: scroll;
}

a.tablinks img {
    width: 30px;
    margin: 0px 10px;
}
a.tablinks {
    font-size: 14px;
    color: #000;
    font-weight: 400;
}
ul.menu-title-sub li a {
    font-weight: 400;
    margin: 10px 10px;
    display: block;
        color: #000;
}
ul.menu-title-sub li {
    width: 100%;
}
</style>
</head>

<body>
    @include('frontend/layout/header')
    @include('frontend/layout/mobile-header')
    @yield('frontend/content')
    <!-- Preloader Start -->
  <div id="preloader-active">
       <div class="preloader d-flex align-items-center justify-content-center">
           <div class="preloader-inner position-relative">
               <div class="text-center">
                   <img src="{{asset('frontend/assets/imgs/theme/Spinner-5.gif')}}" alt="" />
               </div>
           </div>
       </div>
    </div>
    @include('frontend/layout/footer')
    @include('frontend/layout/script')
   <script>
        function openCity(evt, menuName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(menuName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        $(document).on('click', '.tablinks', function (e) {
            var id = $(this).attr('id');
            $("#subcat_div").html('');
            $("#catLoader").css('display', 'block');
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            $.ajax({
                url: "/api/category/" + id,
                type: "get",
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        $("#catLoader").css('display', 'none');

                    } else if (response["status"] == "success") {
                        $("#catLoader").css('display', 'none');
                        $("#subcat_div").html(response["html"]);


                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        })

    </script>

</body>

</html>