<?php

// $payerurl_public_key = '';
// $payerurl_secret_key = '';
$payerurl_secret_key='2d0e809a9eacdca91255b16141c3a3f9';
$payerurl_public_key='6ce24876b8db20b04fe74a767b6276de';

if(!isset($_SERVER['HTTP_AUTHORIZATION']) || empty($_SERVER['HTTP_AUTHORIZATION']))
{
    $authStr = base64_decode($_POST['authStr']);
    $auth = explode(':', $authStr);
}
else
{
    $authStr = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    $authStr = base64_decode($authStr);
    $auth = explode(':', $authStr);
}

$GETDATA = [
    'order_id' => $_POST['order_id'],
    'ext_transaction_id' => isset($_POST['ext_transaction_id']) ? $_POST['ext_transaction_id'] : '',
    'transaction_id' => $_POST['transaction_id'],
    'status_code' => isset($_POST['status_code']) ? (int)$_POST['status_code'] : '',
    'note' => isset($_POST['note']) ? $_POST['note'] : '',
    'confirm_rcv_amnt' => isset($_POST['confirm_rcv_amnt']) ? (float)$_POST['confirm_rcv_amnt'] : 0,
    'confirm_rcv_amnt_curr' => isset($_POST['confirm_rcv_amnt_curr']) ? $_POST['confirm_rcv_amnt_curr'] : '', 
    'coin_rcv_amnt' => isset($_POST['coin_rcv_amnt']) ? (float)$_POST['coin_rcv_amnt'] : 0, 
    'coin_rcv_amnt_curr' => isset($_POST['coin_rcv_amnt_curr']) ? $_POST['coin_rcv_amnt_curr'] : '',
    'authStr' => isset($_POST['authStr']) ? $_POST['authStr'] : ''
];


ksort($GETDATA);
$args = http_build_query($GETDATA);
$signature = hash_hmac('sha256', $GETDATA, $payerurl_secret_key);
$authStr = base64_encode(sprintf('%s:%s', $payerurl_public_key, $signature));
 if($signature != $auth[1]) {
     logTransaction('payerurl', $GETDATA , 'Pending');
     header('Content-Type: application/json; charset=utf-8');
     echo json_encode($signature);
     exit();
 }
 
 
$data =""; 
if($payerurl_public_key != $auth[0])
{
    $data = [ 'status' => 2030,'message' => "Public key doesn\'t match"];
}
else if (!isset($GETDATA['transaction_id']) || empty($GETDATA['transaction_id']))
{
    $data = [ 'status' => 2050,'message' => "Transaction ID not found"];
}

else if (!isset($GETDATA['transaction_id']) || empty($GETDATA['transaction_id']))
{
    $data = [ 'status' => 2050,'message' => "Transaction ID not found"];
}

else if (!isset($GETDATA['order_id']) || empty($GETDATA['order_id']))
{
    $data = [ 'status' => 2050,'message' => "Order ID not found"];
}

else if($GETDATA['status_code'] == 20000) 
{
    $data = [ 'status' => 20000,'message' => "Order Cancelled"];
}
else{
$data = [ 'status' => 2040,'message' => "Order updated successfuly"];
/**
write your code here
write your code here
write your code here
write your code here
write your code here
write your code here
write your code here
**/
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
exit();




?>