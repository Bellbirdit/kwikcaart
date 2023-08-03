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
    <meta property="og:image" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

@include('user/layout/style')
</head>

<body>
    <div class="screen-overlay"></div>
    @include('user/layout/header')

    @include('user/layout/side-bar')

    <main class="main-wrap1" >

     @yield('content')
        <!-- content-main end// -->
        @include('user/layout/footer')

    </main>
    @include('user/layout/script')

    <style>
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1;
        }

        .main-wrap1 {
            margin-left: 300px;
            background-color: #f8f9fa;
            /* position: absolute;
            z-index: -1; */
            /* width: 70%; */
        }

        .card1 {
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #ececec;
            border-radius: .25rem;
        }
    </style>
    <script>
        window.onscroll = function() {myFunction()};

        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;

        function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
        }
    </script>
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

@endif
    @if(!Auth::check())
    <script>window.location.href = "/login";</script>
    @endif