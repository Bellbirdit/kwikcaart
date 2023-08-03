<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safeer Market</title>
   <style>
        * {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .main {
            width: 100%;
            height: auto;
        }

        body {
            max-width: 95%;
            margin: auto;
        }

        .id {
            width: 98%;
            height: auto;
            text-align: right;
            padding-top: 30px;
            padding-bottom: 20px;
        }

        .agent {
            width: 98%;
            height: auto;
            text-align: left;
            padding: 20px;
        }

        .form {
            width: 95%;
            height: auto;
            display: flex;
            margin: auto;
        }

        .form1 {
            height: auto;
            padding:10px;
            text-align: left;
        }

        .border {
            border: none;
        }

        .form2 {
            padding:10px;
            height: auto;
            line-height: 23px;
            text-align: left;
        }

        h3 {
            margin-top: 00px;
        }

        .table {
            width: 100%;
            height: auto;
        }

        table {
            width: 95%;
            border-collapse: collapse;
            margin: 10px auto;
            border: 1px solid rgb(217, 220, 221);
        }

        th,
        td {
           
           text-align:center;
            font-size: 11px;
            border: 1px solid rgb(217, 220, 221);
        }

        th {
            text-align:center;
            font-size: 13px !important;
        }

        .start {
            text-align: left;
            font-size: 12px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        thead {
            background:rgba(59, 183, 126, 0.2);
            
        }

    </style>
</head>

<body>
    <div class="main">
        <div class="form" >
        </div>
        <table style="border:none;">
            <tr>
                <td style="border:none; width:50%"> 
                   <div class="form1" style="padding: 0px; margin-top: -10px;line-height: 1.5;font-size: 12px !important;">
                        <!--<img src="https://safeermarket.com/assets/imgs/theme/logo.png" style="width: 200px;" alt="">-->
                        <h1>Safeermarket.com</h1>
                        <h2>Order To:</h2>
                        <h4>{{\App\Models\User::where(['code' => $order->store_id])->pluck('email')->first()}}</h4>
                        <h4>
                            <strong>Delivery Time: </strong>
                            @if($order->delivery_option == 'self_pickup')
                            Self Pickup {{$order->pick_date}} {{$order->pick_time}}
                            @else
                            Standard Delivery {{$order->pick_date}} {{$order->pick_time}}
                            @endif
                        </h4>
                        <h4><strong>Location : </strong>{!! nl2br(\App\Models\User::where(['code' => $order->store_id])->pluck('address')->first()) !!}</h4>
                    </div>
                </td>
                <td style="border:none; width:50% ">
                    <div class="form2" style="padding: 0px; line-height: 1.5;font-size: 12px !important;">
                        <h1>Order Form</h1>
                        <h3>Order #: {{$order->order_number}}</h3>
                        <h3>Order Date: {{date('d/m/Y')}}</h3>
                        <h2>Customer Details</h2>
                        <h5><strong>Name:</strong> {{$order->first_name}}</h5>
                        <h5><strong>Address:</strong> {!!($order->address)!!}</h5>
                        <h5><strong>Email:</strong> {{$order->email}}</h5>
                        <h5><strong>Phone:</strong> {{$order->phone}}</h5>
                    </div>
                </td>
            </tr>
        </table>
        <div class="table">
            <table>
                <thead>
                    <th style="width:40%; padding:10px;">Product</th>
                    <th style="width:30%; padding:10px;">Barcode</th>
                    <th style="width:20%;padding:10px;">Qty</th>
                    <!-- <th>Unit Price</th> -->
                    <th style="width:20%; padding:10px;">Amount with VAT</th>
                </thead>
                <tbody>
                      @foreach($orders as $detail)
                       <?php $product = App\Models\OrderItem::find($detail->product_id); 
                            $barcode = App\Models\Product::where('id', $detail->product_id)->value('barcode');
                       ?>
                    <tr>
                        <td style="text-align:left; padding:10px;">{{App\Models\Product::where(['id'=>$detail->product_id])->pluck('name')->first()}}</td>
                       
                           @if(!is_null($barcode))
                        <td style="padding:10px;"><img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($barcode, $generator::TYPE_CODE_128)) }}" style="width:120px">
                           <br> {{$barcode}}
                        </td>
                        @else
                            <td style="padding:10px;"> N/A </td>
                        @endif
                        <td class="center" style="padding:10px;">{{$detail->quantity}}</td>
                        <!-- <td class="right">{{$detail->product_price}}</td> -->
                        <td class="right" style="padding:10px;">AED {{$detail->products_quantityprice}}</td>
                    </tr>
                     @endforeach
                    <?php $sum= App\Models\OrderItem::where(['order_id'=>$order->id])->pluck('products_quantityprice')->sum(); ?>
                    <tr>
                        <td colspan="1" class="border"></td>
                        <td class="right" style="padding:10px;" colspan="2"><h3>Subtotal  <small>with VAT</small></h3></td>
                        <td  class="center"><strong>AED {{$sum}}</strong></td>
                    </tr>                      
                    <tr>
                        <td colspan="1" class="border"></td>
                        <td class="right" style="padding:10px;" colspan="2"><h3>Coupon / Discount</h3></td>
                        @if($order->coupondiscount != '')
                        <td  class="center" style="padding:10px;"><strong>AED {{$order->coupondiscount}}</strong></td>
                        @else
                         <td  class="center" style="padding:10px;"><strong>AED 0</strong></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="1" class="border"></td>
                        <td class="right" style="padding:10px;" colspan="2"><h3>Shipping Charges <small> with VAT</small></h3> </td>
                        @if($order->deliverycharges !='')
                        <td  class="center" style="padding:10px;"><strong>AED {{$order->deliverycharges}}</strong></td>
                        @else
                         <td  class="center" style="padding:10px;"><strong>AED 0</strong></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="1" class="border"></td>
                        <td class="right" style="padding:10px;" colspan="2"><h3>VAT</h3></td>
                        
                         <td  class="center" style="padding:10px;"><strong>AED 0</strong></td>
                       
                    </tr>
                   
                    <tr>
                        <td colspan="1" class="border"></td>
                        <td class="right"  style="padding:10px;"colspan="2"><strong>TOTAL AMOUNT</strong></td>
                        <td  class="center" style="padding:10px;">
                            <h2>AED {{$order->coupon_payment}}</h2>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            
        </div>
    </div>
</body>

</html>