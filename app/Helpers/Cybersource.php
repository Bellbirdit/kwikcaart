<?php

namespace App\Helpers;


class Cybersource 
{

    public function CallCyberSourceAPI($method, $data, $gmtDateTime, $digest, $signature)
    {
        $merchantID = config('app.MERCHENT_ID');
        $merchantKeyId = config('app.MERCHENT_KEY_ID');
        $merchantsecretKey = config('app.MERCHENT_SECRET_KEY');
        $requestHost = config('app.MERCHENT_HOST');
        $resource = config('app.MERCHENT_RESOURCE');
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
    
            $httpcode = curl_getinfo($curl);
    
            curl_close($curl);
        } catch (Exception $e) {
            echo '<br><br>Message: ' . $e->getMessage();
        }
    
        return $response;
    }
    
    public function CallCyberSourcePaymentAPI($method, $data, $gmtDateTime, $digest, $signature)
    {
        $merchantID = config('app.MERCHENT_ID');
        $merchantKeyId = config('app.MERCHENT_KEY_ID');
        $merchantsecretKey = config('app.MERCHENT_SECRET_KEY');
        $requestHost = config('app.MERCHENT_HOST');
        $resource = config('app.MERCHENT_RESOURCE');
        $url = "https://" . $requestHost . "/up/v1/payments";
    
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
    
            $httpcode = curl_getinfo($curl);
    
            curl_close($curl);
        } catch (Exception $e) {
            echo '<br><br>Message: ' . $e->getMessage();
        }
    
        return $response;
    }
    
    public function signData($data, $secretKey)
    {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }
    
    public function GenerateSignature($digest, $gmtDateTime, $method, $resource)
    {
        $merchantID = config('app.MERCHENT_ID');
        $merchantKeyId = config('app.MERCHENT_KEY_ID');
        $merchantsecretKey = config('app.MERCHENT_SECRET_KEY');
        $requestHost = config('app.MERCHENT_HOST');
        $resource = config('app.MERCHENT_RESOURCE');
        $url = "https://" . $requestHost . $resource;
        $signatureHeaderValue = "";
        $algorithm = "HmacSHA256";
        $postHeaders = "host date (request-target) digest v-c-merchant-id";
        $getHeaders = "host date (request-target) v-c-merchant-id";
    
        // $resource = "/up/v1/capture-contexts";
        // $url = "https://" . $requestHost . $resource;
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
    
    
    
    public function GenerateDigest($request)
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
}
