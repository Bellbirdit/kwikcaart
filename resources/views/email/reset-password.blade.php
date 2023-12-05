<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap');
    body {
        margin:0;
        padding:0;
    }
    .main {
        width: 100%;
        height: 100%;
        padding: 25px 0px;
        background-color: #C59C39;
        color: #333;
        margin: 0 auto;
        font-family: 'Nunito Sans', sans-serif;
    }
    .rozgar {
        width: 90%;
        max-width: 600px;
        height: auto;
        background-color: white;
        margin: 0 auto;
        position: relative;
        top: 25px;
        border-radius: 20px;
        box-shadow: 4px 8px 16px rgba(0,0,0,0.2)
    }
    .title {
        width: 100%;
        text-align: center;
        height: auto;
        font-size: 1.5rem;
        padding-top: 10px;
    }

        .img {
            width: 100%;
            height: auto;
            text-align: center;
            margin-bottom: 2rem;
        }
        .img img {
            margin-top: 50px; 
            width: 180px;
        }

        .text {
            text-align: center;
            width: 90%;
            font-size: 0.75rem;
            height: auto;
            margin: 0 auto;
        }
        .text h1 {
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        #code {
            color: #C59C39;
            font-size: 2rem;
            font-weight: bolder;
            margin: 35px 0px;
            display: block;
        }
        
        a {
            color: #000;
            text-decoration: none;
        }
        a:hover {
            color: #ffffff;
            text-decoration: none;
        }

        .rozgar a {
            color: #000;
            text-decoration: none;
        }
        .rozgar a:hover {
            color: #C59C39;
        }

        #text {
            padding: 20px;
        }

        .p {
            padding: 0px 30px 30px;
            font-size: 12px;
        }

        .p2 {
            padding: 10px;
            padding-bottom: 40px;
        }

        .icon {
            width: 40%;
            height: auto;
            text-align: center;
            margin: 0 auto;
            padding-top: 10px;
        }

        .p3 {
            width: 42%;
            margin: 0 auto;
            text-align: center;
            padding-bottom: 30px;
        }
        
        .order-summary {
            width: 100%;
            height: auto;
            text-align: center;
        }

        .main-product {
            width: 100%;
            height: auto;
            display: flex;
            margin-top: 30px;
        }

        .product-img {
            width: 40%;
            height: auto;
            border-radius: 30px;
            border: 1px solid gainsboro;
            margin-left: 2%;
        }

        .product-title {
            width: 60%;
            height: auto;
            margin-left: 2%;
            margin-right: 2%;

            border: 1px solid gainsboro;
            border-radius: 30px;
            padding: 20px;

        }

        .title-one {
            width: 50%;
            height: auto;
            float: left;
            line-height: 22px;

        }

        .title-two {
            width: 50%;
            height: auto;
            float: right;
            text-align: right;
            line-height: 22px;
        }

        .order-total {
            width: 100%;
            height: auto;
            text-align: center;
            margin-top: 50px;
        }

        .total-price {
            width: 100%;
            height: auto;
            display: flex;
        }

        .price-one {
            width: 50%;
            height: auto;
            padding: 15px;
        }

        .price-two {
            width: 50%;
            height: auto;
            text-align: end;
            padding: 15px;

        }

        .main-price {
            width: 100%;
            height: auto;
            display: flex;
        }

        .price {
            width: 50%;
            height: auto;
            padding-left: 15px;
            color: rgb(37, 97, 37);
        }

        .price-total {
            width: 50%;
            height: auto;
            text-align: end;
            padding-right: 15px;
            color: rgb(37, 97, 37);
            margin-bottom: 30px;
        }

        .ship {
            width: 100%;
            height: auto;
            text-align: center;
        }

        .bill-ship {
            width: 100%;
            height: auto;
            display: flex;
        }


        .billing {
            width: 40%;
            height: auto;
            padding: 30px;
            margin-left: 100px;
        }

        .shipping {
            width: 40%;
            height: auto;
            padding: 30px;

        }

        .button {
            display:none;
            width: 10%;
            height: auto;
            margin: 0 auto;
            text-align: center;
            border-radius: 20px;
            background-color: rgb(51, 139, 51);
            padding: 15px;
        }

   
        @media screen and (max-width:950px) {
           .main{
            width: 100%;
           }
           .rozgar{
            width: 90%;
           }
           .icon{
            width: 90%;
           }
           .p3{
            width: 90%;
           }
        }
        @media screen and (max-width:500px) {
            .main{
                width: 100%;
           }
           .rozgar{
                width: 100%;
           }
           .icon{
                width: 100%;
           }
           .p3{
                width: 100%;
           }
           #image{
                width:70%;
           }
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="rozgar">
            <div class="img"><img src="{{ URL::asset('/assets/logo/logo.png') }}" id="image" width="350px" alt=""></div>
            <div class="title">
                <h1>Safeer Markeet</h1>
            </div>
            <div class="text">
                <h1 id="text">Hi, </h1>
                <span id="code">CONGRATULATION</span>
                <p class="p">To recover your password please click the button below</p>
                <p class="p">Thank you for your interest in Kwikcaart. </p>
            </div>
        </div>

        <div align="left" class="button-container left"
            style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:25px;">
            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-spacing: 0; border-collapse: collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"><tr><td style="padding-right: 0px; padding-left: 0px; padding-top:0px; padding-bottom:25px;" align="left"><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://example.com/" style="height:36pt; v-text-anchor:middle; width:156pt;" arcsize="9%" strokecolor="#26B4FF" fillcolor="#26B4FF"><w:anchorlock/><v:textbox inset="0,0,0,0"><center style="color:#ffffff; font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size:16px;"><![endif]-->
            <a href="{{url('reset-password',$token)}}" target="_blank"
                style="display: block;text-decoration: none;-webkit-text-size-adjust: none;text-align: center;color: #ffffff; background-color: #26B4FF; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; max-width: 209px; width: 169px;width: auto; border-top: 0px solid transparent; border-right: 0px solid transparent; border-bottom: 0px solid transparent; border-left: 0px solid transparent; padding-top: 8px; padding-right: 20px; padding-bottom: 8px; padding-left: 20px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;mso-border-alt: none">
                <span style="font-size:16px;line-height:32px;"><span
                        style="font-size: 14px; line-height: 28px;"
                        data-mce-style="font-size: 14px;">Reset password</span></span>
            </a>
            <!--[if mso]></center></v:textbox></v:roundrect></td></tr></table><![endif]-->
        </div>
        <div class="icon">
            
        </div> 
        <div class="p3">
            <a href="https://kwikcaart.com/">kwikcaart.com</a>
        </div>
    </div>
</body>

</html>