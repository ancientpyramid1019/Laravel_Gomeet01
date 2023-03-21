<?php

$invoiceid =rand(1111, 999999);
$amount =123;
$currency ='usd';
$billing_fname ='adarsh';
$billing_lname ='Singh';
$billing_email ='adarsh.cubixsys@gmail.com';
// $redirect_to = 'https://www.test.com/checkout/order-pay/1234/?key=wc_order_nBSBH9A6wuFnk';
$redirect_to = 'https://gomeek.net/payerurl-payment/success.php';
$notify_url = 'https://gomeek.net/payerurl-payment/success.php';
// $notify_url = 'https://www.test.com/wc-api/wc_payerurl';

/**********Do not share the credencials*********/  
// get your API key : https://dashboard.payerurl.com/profile/api-management
$payerurl_secret_key='38e9db47a3226a3f8199c41dbf12e5e5';
$payerurl_public_key='a9a962212ebc1465a783ccebdaec89af';
/***********************************************/

    	$args = [
        'order_id' => $invoiceid, 
        //[required field] [String] [Merchant order ID/ Invoice ID]
        'amount' => $amount, 
        //[required field] [String] [Price of the product]
        'currency' => empty($currency)? "usd" : strtolower($currency), 
        //[required field] [String] [Currency of the price of the product]
        'billing_fname' => empty($billing_fname) ? "":$billing_fname, 
        //[Optional field] [String] [Customer billing first name]
        'billing_lname' => empty($billing_lname) ? "":$billing_lname, 
        //[Optional field] [String] [Customer billing last name]
        'billing_email' => empty($billing_email) ? "" : $billing_email, 
        //[Optional field] [String] [Customer billing email]
        'redirect_to' => $redirect_to, 
        //[required field] [String] [After making a purchase, go back to the merchant's website endpoint]
        'notify_url' => $notify_url, 
        //[required field] [String] [After making a purchase, send notification with payment details]
        'type' => 'php',
        //[required field] [String] [The way of the customer request]
    ];
    
    ksort($args);
    $args = http_build_query($args);
    //var_dump($args);
    $signature = hash_hmac('sha256', $args, $payerurl_secret_key);
    //[required field] [Merchant secret key]
    $authStr = base64_encode(sprintf('%s:%s', $payerurl_public_key, $signature));
    //[required field] [Merchant public key]
     //var_dump($authStr);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://dashboard.payerurl.com/api/payment');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type:application/x-www-form-urlencoded;charset=UTF-8',
        'Authorization:' . sprintf('Bearer %s', $authStr),
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = json_decode($response);
    //var_dump($response);
    if($httpCode === 200 && isset($response->redirectTO) && !empty($response->redirectTO)){
        echo "<script>   location.replace('".$response->redirectTO."');  </script> ";
    }

?>