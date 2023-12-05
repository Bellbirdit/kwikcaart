<?php
    //define ('HMAC_SHA256', 'sha256');
    $token_ctp='';
    $merchantID = "magnati_popid";
    $merchantKeyId = "433168af-bee9-407c-b6e8-99e15097dcfc";
    $merchantsecretKey = "7e13SWzGuk9tBFBK9VNvZrRTM0HT5RsPsJrvVB/XCWE=";
    $requestHost = "apitest.cybersource.com";
    $resource = "/up/v1/capture-contexts";
    $url = "https://" . $requestHost . $resource;

    
 
    $body = '{
        "targetOrigins": [
          "https://mrdost.com"
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
          "requestPhone": true,
          "requestShipping": true,
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
          "profile_id": "3067EAE7-F692-4442-A1B8-9AF3DD5FF068",
          "access_key": "c2013ade7d5b3460b686457c4409a739",
          "reference_number": "163311305732",
          "transaction_uuid": "161130335732-001",
          "transaction_type": "authorization",
          "currency": "AED",
          "amount": "1.00",
          "locale": "en-us",
          "override_custom_receipt_page": "https://localhost:8080/CybersourceClickToPayPhp",
          "unsigned_field_names": "transient_token"
        }
      }';
      echo '<br/>' .$body;

      function prnval($val_label,$val_data){
        echo '<br/>';
        echo $val_label.": ".$val_data;
        echo '<br/>';
    
    }


//$timestamp = gmdate("Y-m-d\TH:i:s\Z");
$gmtDateTime = gmdate("D, d M Y H:i:s") . " GMT";
prnval("gmtDateTime",$gmtDateTime);//Sun, 18 Jun 2023 18:42:49 GMT



$digest = GenerateDigest($body);//"2IPyeZYxCkqs5Rf+RV9y9/MEXk6efJPErPFmeT6YHcM="; //GenerateDigest($body);
prnval("digest",$digest);
$signature = GenerateSignature($digest, $gmtDateTime, "post", $resource);
prnval("signature",$signature);

//keyid="57e94c5d-8e0d-40c2-8468-2b6b4a036538", algorithm="HmacSHA256", headers="host date (request-target) digest v-c-merchant-id", signature="R9iMyftR6hmuCF2Rcgl6C6ymowCMR3/VIU1WUnqJoSc="


echo "<br>URL: " . $url;
echo "<br>GMT Date: " . $gmtDateTime;
echo "<br>Digest: " . $digest;
echo "<br>Signature: " . $signature;

//API Call
$result = CallCyberSourceAPI("POST", $body, $gmtDateTime, $digest, $signature);
$token_ctp=$result;
echo "<br><br>API Result: " . $result;

function CallCyberSourceAPI($method, $data, $gmtDateTime, $digest, $signature)
{

    $merchantID = "magnati_popid";
    $merchantKeyId = "433168af-bee9-407c-b6e8-99e15097dcfc";
    $merchantsecretKey = "7e13SWzGuk9tBFBK9VNvZrRTM0HT5RsPsJrvVB/XCWE=";
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
        prnval("response in CallCyberSourceAPI",$response);
        print_r($response);
        //$errors = curl_error($curl);
        //echo '<br><br>Errors:'.$errors;

        $httpcode = curl_getinfo($curl);
        echo '<br><br>HTTP code: ' . $httpcode["http_code"];

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
    $merchantID = "magnati_popid";
    $merchantKeyId = "433168af-bee9-407c-b6e8-99e15097dcfc";
    $merchantsecretKey = "7e13SWzGuk9tBFBK9VNvZrRTM0HT5RsPsJrvVB/XCWE=";
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

        // Generate the Signature
        // $signatureString = "";
        // $signatureString = $signatureString . "host";
        // $signatureString = $signatureString . ": ";
        // $signatureString = $signatureString . $requestHost;
        // $signatureString = $signatureString . '\n';
        // $signatureString = $signatureString . "date";
        // $signatureString = $signatureString . ": ";
        // $signatureString = $signatureString . $gmtDateTime;
        // $signatureString = $signatureString . '\n';
        // $signatureString = $signatureString . "(request-target)";
        // $signatureString = $signatureString . ": ";

        // if ($method == ("post")) {
        //     $signatureString = $signatureString . $postRequestTarget;
        //     $signatureString = $signatureString . '\n';
        //     $signatureString = $signatureString . "digest";
        //     $signatureString = $signatureString . ": ";
        //     $signatureString = $signatureString . $digest;
        // } else {
        //     $signatureString = $signatureString . $getRequestTarget;
        // }

        // $signatureString = $signatureString . '\n';
        // $signatureString = $signatureString . "v-c-merchant-id";
        // $signatureString = $signatureString . ": ";
        // $signatureString = $signatureString . $merchantID;
        // $signatureString = ltrim($signatureString, "\n");

        prnval("signatureString",$signatureString);

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
    prnval("digest1",$digest);
    var_dump($digest);


    $digest = "DIGEST_PLACEHOLDER";
    try {
        $payloadBytes = hash("sha256", $request, true);
        $digest = "SHA-256=" . base64_encode($payloadBytes);
        prnval("digest2",$digest);
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
echo "PARSE "."<br/>";

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
echo "Status: " . $status . "\n";
echo "Headers:\n";
print_r($headers);
echo '<br/>';
echo 'Here it is <br/>';
echo "Body: " . $body . "\n";
echo '<br/>';
$token_ctp=$body;

echo "PARSE END"."<br/>";
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