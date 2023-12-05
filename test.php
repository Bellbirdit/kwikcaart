<?php
//define ('HMAC_SHA256', 'sha256');

if(isset($_POST) and !empty($_POST)){
    echo "<pre>";
    print_r($_POST);
    die;
}


$token_ctp='';
$merchantID = "fab_securepay";
$merchantKeyId = "57e94c5d-8e0d-40c2-8468-2b6b4a036538";
$merchantsecretKey = "0/bhCLt76AsxGUFoWF+oGdXNleqHLojAtX1zwGx10NE=";
$requestHost = "apitest.cybersource.com";
$resource = "/up/v1/capture-contexts";
$url = "https://" . $requestHost . $resource;

 
    // $body = '{
    //     "targetOrigins": [
    //       "https://kwikcaart.com"
    //     ],
    //     "clientVersion": "0.10",
    //     "allowedCardNetworks": [
    //       "VISA",
    //       "MASTERCARD",
    //       "AMEX"
    //     ],
    //     "allowedPaymentTypes": [
    //       "PANENTRY",
    //       "SRC"
    //     ],
    //     "country": "AE",
    //     "locale": "en_US",
    //     "captureMandate": {
    //       "billingType": "FULL",
    //       "requestEmail": true,
    //       "requestPhone": false,
    //       "requestShipping": false,
    //       "shipToCountries": [
    //         "US",
    //         "GB"
    //       ],
    //       "showAcceptedNetworkIcons": true
    //     },
    //     "orderInformation": {
    //       "amountDetails": {
    //         "totalAmount": "1.00",
    //         "currency": "AED"
    //       }
    //     },
    //     "checkoutApiInitialization": {
    //       "profile_id": "485A2985-D800-4335-A54F-964E81B37B6F",
    //       "access_key": "76d319548a4f3613998bf4427fb49668",
    //       "reference_number": "163311305732",
    //       "transaction_uuid": "161130335732-001",
    //       "transaction_type": "sale,create_payment_token",
    //       "currency": "AED",
    //       "amount": "1.00",
    //       "locale": "en-us",
    //       "override_custom_receipt_page": "https://kwikcaart.com/test.php?success=true",
    //       "unsigned_field_names": "transient_token"
    //     }
    //   }';
    
    $body = '
    {
  "targetOrigins": [
    "https://kwikcaart.com"
  ],
  "clientVersion": "0.10",
  "allowedCardNetworks": [
    "VISA",
    "MASTERCARD",
    "AMEX"
  ],
  "allowedPaymentTypes": [
    "PANENTRY",
    "SRC"
  ],
  "country": "AE",
  "locale": "en_US",
  "captureMandate": {
    "billingType": "FULL",
    "requestEmail": true,
    "requestPhone": false,
    "requestShipping": false,
    "shipToCountries": [
      "US",
      "GB"
    ],
    "showAcceptedNetworkIcons": true
  },
  "orderInformation": {
    "amountDetails": {
      "totalAmount": "1.00",
      "currency": "AED"
    }
  },
  "checkoutApiInitialization": {
    "profile_id": "485A2985-D800-4335-A54F-964E81B37B6F",
    "access_key": "76d319548a4f3613998bf4427fb49668",
    "reference_number": "163311305732",
    "transaction_uuid": "161130335732-001",
    "transaction_type": "sale,create_payment_token",
    "currency": "AED",
    "amount": "1.00",
    "locale": "en-us",
    "override_custom_receipt_page": "https://kwikcaart.com/test.php?success=true",
    "unsigned_field_names": "transient_token"
  }
}';

// authorization,create_payment_token
// payment_token in response 

/*

{

  "clientReferenceInformation": {

    "code": "TC50171_3"

  },

  "processingInformation": {

    "capture": true

  },

  "paymentInformation": {

    "legacyToken": {

      "id": ""

    }

  },

  "orderInformation": {

    "amountDetails": {

      "totalAmount": "102.21",

      "currency": "USD"

    },

    "billTo": {

      "firstName": "John",

      "lastName": "Doe",

      "address1": "1 Market St",

      "locality": "san francisco",

      "administrativeArea": "CA",

      "postalCode": "94105",

      "country": "US",

      "email": "test@cybs.com",

      "phoneNumber": "4158880000"

    }

  }

}

https://apitest.cybersource.com/pts/v2/payments
https://developer.cybersource.com/api-reference-assets/index.html#payments_payments_process-a-payment_samplerequests-dropdown_authorization-with-capture-sale_liveconsole-tab-request-body
*/

$gmtDateTime = gmdate("D, d M Y H:i:s") . " GMT";



$digest = GenerateDigest($body);
$signature = GenerateSignature($digest, $gmtDateTime, "post", $resource);

//API Call
$result = CallCyberSourceAPI("POST", $body, $gmtDateTime, $digest, $signature);
$token_ctp=$result;
// echo "<br><br>API Result: " . $result;

function CallCyberSourceAPI($method, $data, $gmtDateTime, $digest, $signature)
{

    $merchantID = "fab_securepay";
    $merchantKeyId = "57e94c5d-8e0d-40c2-8468-2b6b4a036538";
    $merchantsecretKey = "0/bhCLt76AsxGUFoWF+oGdXNleqHLojAtX1zwGx10NE=";
    $requestHost = "apitest.cybersource.com";
    $resource = "/up/v1/capture-contexts";
    $url = "https://" . $requestHost . $resource;

    try {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSLVERSION => 6,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => array(
                    'v-c-merchant-id: ' . $merchantID,
                    'Date: ' . $gmtDateTime,
                    'Host: ' . $requestHost,
                    'Digest:' . $digest,
                    'Signature: ' . $signature,
                    'Content-Type: application/json'
                ),
            )
        );

        $response = curl_exec($curl);
        echo  $response;
        $httpcode = curl_getinfo($curl);

        curl_close($curl);
    } catch (Exception $e) {
        echo '<br><br>Message: ' . $e->getMessage();
    }

    return $response;
}

function signData($data, $secretKey)
{
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function GenerateSignature($digest, $gmtDateTime, $method, $resource)
{
    $requestHost = "apitest.cybersource.com";
    $merchantID = "fab_securepay";
    $merchantsecretKey = "0/bhCLt76AsxGUFoWF+oGdXNleqHLojAtX1zwGx10NE=";
    $merchantKeyId = "57e94c5d-8e0d-40c2-8468-2b6b4a036538";
    $signatureHeaderValue = "";
    $algorithm = "HmacSHA256";
    $postHeaders = "host date (request-target) digest v-c-merchant-id";
    $getHeaders = "host date (request-target) v-c-merchant-id";

    $resource = "/up/v1/capture-contexts";
    $url = "https://" . $requestHost . $resource;
    $getRequestTarget = $method . " " . $resource;
    $postRequestTarget = $method . " " . $resource;

    try {
        $signatureString = "\n";
        $signatureString .= "host" . ": " . $requestHost . "\n";
        $signatureString .= "date" . ": " . $gmtDateTime . "\n";
        $signatureString .= "(request-target)" . ": ";

        if ($method === "post") {
            $signatureString .= $postRequestTarget . "\n";
            $signatureString .= "digest" . ": " . $digest;
        } else {
            $signatureString .= $getRequestTarget;
        }

        $signatureString .= "\n";
        $signatureString .= "v-c-merchant-id" . ": " . $merchantID;
        $signatureString = ltrim($signatureString, "\n");

        $signatureByteString = utf8_encode($signatureString);
        $decodedKey = base64_decode($merchantsecretKey);
        $hashmessage = hash_hmac("sha256", $signatureByteString, $decodedKey, true);

        $base64EncodedSignature = base64_encode($hashmessage);
   
        $signatureHeaderValue = $signatureHeaderValue . "keyid=\"" . $merchantKeyId . "\"";
        $signatureHeaderValue = $signatureHeaderValue . ", algorithm=\"" . $algorithm . "\"";

        if ($method == "post") {
            $signatureHeaderValue = $signatureHeaderValue . ", headers=\"" . $postHeaders . "\"";
        } else if ($method . Equals("get")) {
            $signatureHeaderValue = $signatureHeaderValue . ", headers=\"" . $getHeaders . "\"";
        }

        $signatureHeaderValue = $signatureHeaderValue . ", signature=\"" . $base64EncodedSignature . "\"";
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }

    return $signatureHeaderValue;
}



function GenerateDigest($request)
{

    $digest = base64_encode(hex2bin(hash('sha256', $request)));
    $digest = "SHA-256=" . $digest;

    $digest = "DIGEST_PLACEHOLDER";
    try {
        $payloadBytes = hash("sha256", $request, true);
        $digest = "SHA-256=" . base64_encode($payloadBytes);
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
    }
    return $digest;
}
?>


<!DOCTYPE html>
<html>
<title>Click To Pay</title>

<style>
    * {
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
    }

    body {
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        margin: 0;
    }

    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .btn:active {
        background-image: none;
        box-shadow: rgba(0, 0, 0, 0.125) 0px 3px 5px inset;
        outline: 0px;
    }

    .btn[disabled] {
        cursor: not-allowed;
        opacity: 0.65;
        box-shadow: none;
    }

    .btn-default {
        color: rgb(51, 51, 51);
        background-color: rgb(255, 255, 255);
        border-color: rgb(204, 204, 204);
    }

    .btn-default:focus {
        color: rgb(51, 51, 51);
        background-color: rgb(230, 230, 230);
        border-color: rgb(140, 140, 140);
    }

    .btn-default:hover {
        color: rgb(51, 51, 51);
        background-color: rgb(230, 230, 230);
        border-color: rgb(173, 173, 173);
    }

    .btn-primary {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }

    .btn-primary:focus {
        color: rgb(255, 255, 255);
        background-color: rgb(40, 96, 144);
        border-color: rgb(18, 43, 64);
    }

    .btn-primary:hover {
        color: rgb(255, 255, 255);
        background-color: rgb(40, 96, 144);
        border-color: rgb(32, 77, 116);
    }

    .btn-lg {
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.3333333;
        border-radius: 6px;
    }

    .navbar-header {
        padding: 0.8em;
    }

    hr {
        border-top: 2px solid #222;
        margin: 1em 0;
    }

    img.media-object {
        max-width: 80px;
    }

    .navbar-default {
        background-color: #d8d8d8;
        border: none;
        margin-bottom: 20px;
    }

    .navbar-default .navbar-brand {
        color: #222;
        font-size: 1.2em;
    }

    .panel {
        margin-bottom: 20px;
        background-color: #fff;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .panel-heading {
        padding: 10px 15px;
        border-bottom: 1px solid transparent;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
    }

    .panel-title {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 16px;
        color: inherit;
    }

    .panel-default {
        border-color: #ddd;
    }

    .panel-success {
        border-color: #d6e9c6;
    }

    .panel-danger {
        border-color: #ebccd1;
    }

    .panel-default>.panel-heading {
        color: #333;
        background-color: #f5f5f5;
        border-color: #ddd;
    }

    .panel-success>.panel-heading {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .panel-danger>.panel-heading {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }

    .container {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        width: 80%;
    }

    #show-capture-context-btn {
        float: right;
        margin-top: 6px;
    }

    #leftPanelContainer {
        padding-bottom: 15px;
        flex: 2;
    }

    #rightPanelContainer {
        flex: 1;
        margin-left: 1em;
        height: 100%;
        max-width: 360px;
        width: 100%;
    }

    .cart-items>.cart-item {
        margin-bottom: 1em;
    }

    .cart-item {
        display: flex;
        padding: 1em;
        background: #d8d8d8;
    }

    .cart-item-body {
        margin-left: 1em;
    }

    #buttonPaymentListContainer {
        display: flex;
        flex-direction: column;
    }

    #buttonPaymentListContainer>.btn:not(:last-child) {
        margin-bottom: 0.5em;
    }

    pre {
        display: block;
        padding: 9.5px;
        margin: 0 0 10px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #333;
        word-break: break-all;
        word-wrap: break-word;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 4px;
        white-space: pre-wrap;
    }

    .panel-body {
        padding: 1em;
    }

    p {
        margin: 0.2em 0;
    }

    h4 {
        margin: 0.5em 0;
        font-weight: 200;
        font-size: 1.2em;
    }

    .row {
        display: flex;
        width: 100%;
    }

    .pull-right {
        float: right;
    }

    @@media (max-width: 900px) {
        .row {
            flex-direction: column;
        }

        #leftPanelContainer {
            flex: 1;
        }

        #rightPanelContainer {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>

<body>
<?php
// echo "PARSE "."<br/>";

// Split the result into headers and body
list($headerString, $body) = explode("\r\n\r\n", $result, 2);

// Parse the headers
$headers = [];
$headerLines = explode("\r\n", $headerString);
foreach ($headerLines as $line) {
    $parts = explode(": ", $line);
    if (count($parts) === 2) {
        $headerName = $parts[0];
        $headerValue = $parts[1];
        $headers[$headerName] = $headerValue;
    }
}

// Extract the status code
$status = $headers['STATUS_CODE'];

// Output the results
// echo "Status: " . $status . "\n";
// echo "Headers:\n";
// print_r($headers);
// echo '<br/>';
// echo 'Here it is <br/>';
// echo "Body: " . $body . "\n";
// echo '<br/>';
$token_ctp=$body;

// echo "PARSE END"."<br/>";
?>

    <div id="embeddedPaymentContainer" style="margin-top:50px">
        <div class="container" role="main" style="margin-top:50px">
            <div id="capture-context-display-container"></div>
            <div class="row">
                <div id="leftPanelContainer">
                    <h4>Shopping Cart (2 items)</h4>
                    <div class="cart-items">
                        <div class="cart-item">
                            <div class="media-left">
                                <!-- <a href="#">
                                    <img class="media-object" src="~/imges/product-1.png" alt="Product 1" />
                                </a> -->
                            </div>
                            <div class="cart-item-body">
                                <h4 class="media-heading">Item Name</h4>
                                <p>Product details</p>
                            </div>
                        </div>
                        <div class="cart-item">
                            <div class="media-left">
                                <!-- <a href="#">
                                    <img class="media-object" src="~/imges/product-2.png" alt="Product 2" />
                                </a> -->
                            </div>
                            <div class="cart-item-body">
                                <h4 class="media-heading">Item Name</h4>
                                <p>Product details</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="rightPanelContainer">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>Order Summary</h4>
                            <p>Subtotal (2 items)<span class="pull-right">AED 1.00</span></p>
                            <p>Shipping<span class="pull-right">AED 0.00</span></p>
                            <p>Estimated tax (98074)<span class="pull-right">AED 0.00</span></p>
                            <hr />
                            <h4>Order Total<span class="pull-right">AED 1.00</span></h4>
                            <hr />

                            <div id="buttonPaymentListContainer">
                                <button type="button" id="checkoutEmbedded" class="btn btn-lg btn-block btn-primary"
                                    disabled="disabled">
                                    Loading...
                                </button>
                                <button type="button" id="checkoutSidebar" class="btn btn-lg btn-block btn-primary"
                                    disabled="disabled">
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="authForm" action="https://testsecureacceptance.cybersource.com/silent/pay" method="post" target="">
            <input type="hidden" id="capturecontext" name="capture_context" 
value="<?php echo $token_ctp; ?>" />

            <input type="hidden" id="transientToken" name="transient_token" />
            
        </form>
    </div>



</body>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script src="https://apitest.cybersource.com/up/v1/assets/0.10.0/SecureAcceptance.js"></script>
<script>
    $(document).ready(function () {
        var authForm = document.getElementById("authForm");
        var transientToken = document.getElementById("transientToken");
        var cc = document.getElementById("capturecontext").value;

        var showArgs = {
            containers: {
                paymentSelection: "#buttonPaymentListContainer"
                //paymentScreen: "#embeddedPaymentContainer"
            }
        };
        Accept(cc)
            .then(function (accept) {
                //return accept.unifiedPayments(false);
                return accept.unifiedPayments();
            })
            .then(function (up) {
                return up.show(showArgs);
            })
            .then(function (tt) {
                transientToken.value = tt;
                //merchant should handle tt accordingly for example submit the tt for processing
                authForm.submit();
            }).catch(function (error) {
                //merchant logic for handling issues
                console.log(error, "error");
                //alert("something went wrong");
            });
    });

</script>

</html>