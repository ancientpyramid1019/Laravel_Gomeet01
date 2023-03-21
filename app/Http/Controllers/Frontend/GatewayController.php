<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\GatewayType;
use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Invest;
use App\Models\Transaction;
use App\Traits\MailSendTrait;
use charlesassets\LaravelPerfectMoney\PerfectMoney;
use Crypt;
use Illuminate\Http\Request;
use Modules\Payment\CoinPayments\CoinPaymentsAPI;
use Modules\Payment\Monnify\Monnify;
use Mollie\Laravel\Facades\Mollie;
use Paystack;
use SecurionPay\Exception\SecurionPayException;
use SecurionPay\SecurionPayGateway;
use Session;
use Shakurov\Coinbase\Facades\Coinbase;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Txn;
use URL;

class GatewayController extends Controller
{
    use MailSendTrait;

    public function gateway($code)
    {
        $Gateway = Gateway::code($code)->select('name', 'charge', 'minimum_deposit', 'maximum_deposit', 'charge_type', 'logo', 'type')->first();

        if ($Gateway->type == GatewayType::Manual) {
            $Gateway = Gateway::code($code)->select('name', 'charge', 'minimum_deposit', 'maximum_deposit', 'charge_type', 'logo', 'type', 'credentials', 'payment_details')->first();

            $credentials = $Gateway->credentials;
            $paymentDetails = $Gateway->payment_details;

            $Gateway = array_merge($Gateway->toArray(), ['credentials' => view('frontend.gateway.include.manual', compact('credentials', 'paymentDetails'))->render()]);
        }

        return $Gateway;
    }

    //list json
    public function gatewayList()
    {
        $gateways = Gateway::where('status',1)->get();
        return view('frontend.gateway.include.__list', compact('gateways'));
    }

    //  Paypal
    public function paypalGateway(Request $request)
    {

        $depositTnx = Session::get('deposit_tnx');
        $tnxInfo = Transaction::tnx($depositTnx);


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('gateway.paypal.success'),
                "cancel_url" => route('gateway.paypal.cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => $tnxInfo->pay_currency,
                        "value" => $tnxInfo->pay_amount,
                    ],
                    'reference_id' => $depositTnx,

                ]
            ],
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Something went wrong.');

        } else {
            return redirect()
                ->route('user.dashboard')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);


        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $txn = $response['purchase_units'][0]['reference_id'];

            return self::paymentSuccess($txn);

        } else {
            return redirect()
                ->route('user.deposit.now')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function paypalCancel()
    {
        return redirect(route('status.cancel'));
    }

    protected function paypal()
    {
        $data['view'] = 'frontend.gateway.submit.paypal';
        $data['action'] = route('gateway.paypal');
        $data['account'] = 'post';
        return $data;
    }


   // stripe
    public function stripeGateway(Request $request)
    {


        $ref = Crypt::decryptString($request->reftrn);

        return self::paymentSuccess($ref);

    }

   // Perfect Money
    public function perfectMoney(Request $request)
    {
        $ref = Crypt::decryptString($request->PAYMENT_ID);
        return self::paymentSuccess($ref);
    }

    // mollie
    public function mollieGateway(Request $request)
    {

        $paymentId = Session::get('m_id');
        $payment = Mollie::api()->payments()->get($paymentId);


        if ($payment->isPaid()) {
            $ref = Crypt::decryptString($request->reftrn);
            return self::paymentSuccess($ref);
        }

        return redirect(route('status.cancel'));

    }

    //coinbase
    public function coinbase(Request $request)
    {
        $ref = Crypt::decryptString($request->reftrn);
        return self::paymentSuccess($ref);
    }

    //paystack
    public function paystackCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        if ($paymentDetails['data']['status'] == 'success') {

            $transactionId = $paymentDetails['data']['reference'];

            return self::paymentSuccess($transactionId);


        } else {
            return redirect()->route('status.cancel');
        }
    }

    //voguepay
    public function voguepaySuccess(Request $request)
    {
        $ref = Crypt::decryptString($request->reftrn);
        return self::paymentSuccess($ref);
    }
    protected function voguepaySubmit($info)
    {
        $data['info'] = $info;
        $data['view'] = 'frontend.gateway.submit.voguepay';
        $data['action'] = 'https://pay.voguepay.com';
        $data['method'] = 'POST';
        return $data;
    }

    //flutterwaveSuccess
    public function flutterwaveProcess(Request $request)
    {



        if(isset($_GET['status']))
        {
            //* check payment status


            $txnid = $_GET['tx_ref'];
            $txnInfo = Transaction::tnx($txnid);

            if($_GET['status'] == 'cancelled')
            {
                // echo 'YOu cancel the payment';
                $txnInfo->update([
                    'status' => TxnStatus::Failed,
                ]);

                if ($txnInfo->type == TxnType::Investment) {

                    notify()->warning('YOu cancel the payment', 'cancelled');
                    return redirect()->route('user.invest-logs');

                } else {

                    notify()->warning('YOu cancel the payment', 'cancelled');
                    return redirect()->route('user.deposit.amount');
                }
            }
            elseif($_GET['status'] == 'successful')
            {

                $txid = $_GET['transaction_id'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Bearer FLWSECK_TEST-efc192c9a48969fc259c517aef8bcc82-X".gateway_info('flutterwave')->secret_key
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                $res = json_decode($response);


                if($res->status)
                {
                    $amountPaid = $res->data->charged_amount;
                    $amountToPay = $res->data->meta->price;
                    if($amountPaid >= $amountToPay)
                    {
                        return self::paymentSuccess($txnid);
                    }
                    else
                    {

                        $txnInfo->update([
                            'status' => TxnStatus::Failed,
                        ]);

                        if ($txnInfo->type == TxnType::Investment) {

                            notify()->warning('Fraud transactio detected', 'detected');
                            return redirect()->route('user.invest-logs');

                        } else {

                            notify()->warning('Fraud transactio detected', 'detected');
                            return redirect()->route('user.deposit.amount');
                        }
                    }
                }
                else
                {

                    $txnInfo->update([
                        'status' => TxnStatus::Failed,
                    ]);

                    if ($txnInfo->type == TxnType::Investment) {

                        notify()->warning('Can not process payment', 'not process');
                        return redirect()->route('user.invest-logs');

                    } else {

                        notify()->warning('Can not process payment', 'not process');
                        return redirect()->route('user.deposit.amount');
                    }

                }
            }
        }



    }

    //congate callbak
    public function coingateProcess(Request $request)
    {

        if ($request->status == 'paid'){
            self::paymentSuccess($request->order_id,$request->user_id);
        }else{
            Txn::update($request->order_id, 'failed',$request->user_id);
        }
    }
    public function coingateSuccess()
    {
        return redirect(URL::temporarySignedRoute(
            'status.success', now()->addMinutes(2)
        ));
    }
    public function coingateCancel(Request $request)
    {
        return redirect()->route('status.cancel');
    }

    //monnify callbak
    public function monnifyCallback(Request $request)
    {
        (isset($_GET) && isset($_GET['paymentReference'])) ?
            ( $ref = htmlspecialchars($_GET['paymentReference']) ) : $ref = NULL;
        $trx = Session::get('deposit_tnx');
        $txnInfo = Transaction::tnx($trx);
        if(htmlspecialchars($_GET['paymentReference'])){

            //Query the transaction reference from your DB call the method

            $monnify = new Monnify();

            $verify = $monnify->verifyTrans($txnInfo->approval_cause);


            if($verify['paymentStatus'] == 'PAID'){
                $txnInfo->update([
                    'approval_cause' => 'none'
                 ]);

                return self::paymentSuccess($ref);

                //Payment has been verified!

            }else{
                $txnInfo->update([
                    'approval_cause' => 'none'
                ]);
                return redirect()->route('status.cancel');
            }

        }else{
            $txnInfo->update([
                'approval_cause' => 'none'
            ]);
            return redirect()->route('status.cancel');
        }
    }

    protected function flutterwave($info)
    {
        //* Ca;; f;iterwave emdpoint
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($info),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.gateway_info('flutterwave')->secret_key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);



        if($res->status == 'success')
        {
            $link = $res->data->link;
            return redirect($link);
        }
        else
        {
            $txnInfo = Transaction::tnx($info['tx_ref']);
            // echo 'YOu cancel the payment';
            $txnInfo->update([
                'status' => TxnStatus::Failed,
            ]);

            if ($txnInfo->type == TxnType::Investment) {

                notify()->warning('We can not process your payment', 'can not process');
                return redirect()->route('user.invest-logs');

            } else {

                notify()->warning('We can not process your payment', 'can not process');
                return redirect()->route('user.deposit.amount');
            }
        }
    }

    //
    protected function securionPay($amount)
    {
        $data['amount'] = $amount;
        $data['view'] = 'frontend.gateway.submit.securion_pay';
        return $data;
    }

    public function securionPayNow(Request $request)
    {
            $depositTnx = Session::get('deposit_tnx');
            $tnxInfo = Transaction::tnx($depositTnx);

            $cardDate = explode('/',$request->card_date);
            if (!isset($cardDate[1])){
                abort(406 ,'Please Valid Card Expiry Date');
            }

            $card = [
              'number' => $request->card_number,
              'exp_month' => $cardDate[0],
              'exp_year' => $cardDate[1],
            ];

            $gatewayInfo = gateway_info('securionpay');
            $gateway = new SecurionPayGateway($gatewayInfo->secret_key);

            $request = array(
                'amount' => $tnxInfo->pay_amount,
                'currency' => $tnxInfo->pay_currency,
                'card' => array(
                    'number' => $card['number'],
                    'expMonth' => $card['exp_month'],
                    'expYear' => $card['exp_year']
                )
            );

            try {
                $charge = $gateway->createCharge($request);
                if ($charge->getStatus() == 'successful' && $charge->getAmount() == (int)$tnxInfo->pay_amount){
                    return self::paymentSuccess($depositTnx);
                }else{
                    abort(406 ,'error');
                }

            } catch (SecurionPayException $e) {
                $errorMessage = $e->getMessage();
                abort(406 ,$errorMessage);

            }
    }
    
    public function payerurlSuccess(Request $request)
    {
        
        // return "gomeek success";
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
        // return response()->json($data);
        exit();

       
    }
    

//    ============== Payment Success ===========================
    private function paymentSuccess($ref,$userId=null)
    {
        $txnInfo = Transaction::tnx($ref);
        if ($txnInfo->type == TxnType::Investment) {

            $investmentInfo = Invest::where('transaction_id', $txnInfo->id)->first();
            $investmentInfo->update([
                'status' => InvestStatus::Ongoing,
                'created_at' => now(),
            ]);

            $txnInfo->update([
                'status' => TxnStatus::Success,
            ]);

            notify()->success('Successfully Investment', 'success');
            return redirect()->route('user.invest-logs');

        } else {

            $txnInfo->update([
                'status' => TxnStatus::Success,
            ]);
            Txn::update($ref, 'success',$userId);
            return redirect(URL::temporarySignedRoute(
                'status.success', now()->addMinutes(2)
            ));
        }
    }


//    ============== Payment Direct Gateway ===========================
    protected function directGateway($gateway, $txnInfo)
    {

        $txn = $txnInfo->tnx;
        Session::put('deposit_tnx', $txn);

        if ($gateway == 'paypal') {
            $data = self::paypal();
        }
        elseif ($gateway == 'stripe') {


            $stripeCredential = gateway_info('stripe');


            \Stripe\Stripe::setApiKey($stripeCredential->stripe_secret);

            $session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => $txnInfo->pay_currency,
                        'product_data' => [
                            'name' => $txnInfo->description,
                        ],
                        'unit_amount' => $txnInfo->pay_amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('gateway.stripe', ['reftrn' => Crypt::encryptString($txnInfo->tnx)]),
                'cancel_url' => route('status.cancel'),
            ]);
            return redirect($session->url);
        }
        elseif ($gateway == 'mollie') {

            $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => $txnInfo->pay_currency, // Type of currency you want to send
                    'value' => (string)$txnInfo->pay_amount . '.00', // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => $txnInfo->description,
                'redirectUrl' => route('gateway.mollie', 'reftrn=' . Crypt::encryptString($txn)),
            ]);


            Session::put('m_id', $payment->id);
            $payment = Mollie::api()->payments()->get($payment->id);

            // redirect customer to Mollie checkout page
            return redirect($payment->getCheckoutUrl(), 303);
        }
        elseif ($gateway == 'perfectmoney') {

            $paymentUrl = route('gateway.perfectMoney');
            $noPaymentUrl = route('status.cancel');
            return PerfectMoney::render(['PAYMENT_AMOUNT' => $txnInfo->pay_amount, 'PAYMENT_ID' => $txn, 'PAYMENT_URL' => $paymentUrl, 'PAYMENT_UNITS' => $txnInfo->pay_currency, 'NOPAYMENT_URL' => $noPaymentUrl, 'NOPAYMENT_URL_METHOD' => 'GET']);

        }
        elseif ($gateway == 'coinbase') {

            $charge = Coinbase::createCharge([
                'name' => 'Deposit no #' . $txn,
                'description' => 'Deposit',
                "cancel_url" => route('status.cancel'),

                'local_price' => [
                    'amount' => $txnInfo->pay_amount,
                    'currency' => $txnInfo->pay_currency,
                ],
                'pricing_type' => 'fixed_price',
                'redirect_url' => route('gateway.coinbase', 'reftrn=' . Crypt::encryptString($txn))
            ]);

            return redirect($charge['data']['hosted_url']);

        }
        elseif ($gateway == 'paystack') {

            $data = array(
                "amount" => $txnInfo->pay_amount*100,
                "reference" => $txn,
                "email" => auth()->user()->email,
                "currency" => $txnInfo->pay_currency,
                "orderID" => $txn,
            );


            return \Unicodeveloper\Paystack\Facades\Paystack::getAuthorizationUrl($data)->redirectNow();


        }
        elseif ($gateway == 'voguepay') {
            $info = [
                'merchant_id' => gateway_info('voguepay')->merchant_id,
                'email' => auth()->user()->email,
                'amount' => $txnInfo->pay_amount,
                'currency' => $txnInfo->pay_currency,
                'success_url' => route('gateway.voguepay.success', 'reftrn=' . Crypt::encryptString($txn)),
            ];
            $data = $this->voguepaySubmit($info);
        }
        elseif ($gateway == 'flutterwave') {
            $info = [
                'tx_ref' => $txn,
                'amount' => $txnInfo->pay_amount,
                'currency' => $txnInfo->pay_currency,
                'payment_options' => 'card',
                'redirect_url' => route('gateway.flutterwave.callback'),
                'customer' => [
                    'email' => auth()->user()->email,
                    'name' => auth()->user()->full_name
                ],
                'meta' => [
                    'price' => $txnInfo->pay_amount
                ],
                'customizations' => [
                    'title' => 'Paying for a sample product',
                    'description' => 'sample'
                ]
            ];
            return self::flutterwave($info);
        }
        elseif ($gateway == 'coingate') {

            $client = new \CoinGate\Client('NPfn5eAGjha_PqfQmC6F_rMA6_zaGVLmVk6Uvsfu', true);

            $params = [
                'order_id'          => $txn,
                'price_amount'      => $txnInfo->pay_amount,
                'price_currency'    => $txnInfo->pay_currency,
                'receive_currency'  => 'EUR',
                'callback_url'      => route('gateway.coingate.callback',['user_id' => auth()->user()->id]),
                'cancel_url'        => route('gateway.coingate.cancel'),
                'success_url'       => route('gateway.coingate.success'),
                'title'             => auth()->user()->full_name,
                'description'       => auth()->user()->email
            ];

            $status = $client->order->create($params);

            return redirect($status->payment_url);
        }
        elseif ($gateway == 'monnify') {
            $monnifyCredential = gateway_info('monnify');
            $monnify = new Monnify();
            $data= array(
                "amount"=> $txnInfo->pay_amount,
                "customerName"=> auth()->user()->full_name,
                "customerEmail"=> auth()->user()->email,
                "paymentReference" => $txn,
                "paymentDescription"=> "GMAS Premium Membership",
                "currencyCode"=> $txnInfo->pay_currency,
                "contractCode"=> $monnifyCredential->contract_code,
                "redirectUrl"=> route('gateway.monnify.callback'),
                "paymentMethods"=> [
                    "CARD",
                    "ACCOUNT_TRANSFER"
                ]);

            //Initialize transaction and redirect to checkout url

            $init = $monnify->initTrans($data);


            $txnInfo->update([
                'approval_cause' =>  $init['transactionReference']
            ]);

            if($init['checkoutUrl']){

                return redirect($init['checkoutUrl']);

            }else{
                $txnInfo->update([
                    'approval_cause' => 'none'
                ]);
                return redirect()->route('status.cancel');
            }
        }
        elseif ($gateway == 'securionpay') {

            $amountInfo = $txnInfo->pay_amount.$txnInfo->pay_currency;
            $data = self::securionPay($amountInfo);
        }
        elseif ($gateway == 'coinpayments') {

            $cps = new CoinPaymentsAPI();
            $cps->Setup(gateway_info('coinpayments')->private_key, gateway_info('coinpayments')->public_key);

            $req = array(
                'amount' => $txnInfo->pay_amount,
                'currency1' => 'USD',
                'currency2' => $txnInfo->pay_currency,
                'buyer_email' => '',
                'item_name' => 'Test Item/Order Description',
                'address' => '', // leave blank send to follow your settings on the Coin Settings page
                'ipn_url' => route('ipn.coinpayments'),
            );

            $result = $cps->CreateTransaction($req);
            if ($result['error'] == 'ok') {
                return redirect($result['result']['checkout_url']);

            } else {
                // echo 'YOu cancel the payment';
                $txnInfo->update([
                    'status' => TxnStatus::Failed,
                ]);

                if ($txnInfo->type == TxnType::Investment) {

                    notify()->warning('please provide valid api info', 'can not process');
                    return redirect()->route('user.invest-logs');

                } else {

                    notify()->warning('please provide valid api info', 'can not process');
                    return redirect()->route('user.deposit.amount');
                }
            }


        }
        elseif ($gateway == 'payerurl') {

            // dd($gateway,$txnInfo);
            
            $invoiceid =rand(1111, 999999);
            $amount = $txnInfo->amount;
            $currency ='usd';
            $billing_fname ='adarsh';
            $billing_lname ='Singh';
            $billing_email ='adarsh.cubixsys@gmail.com';
            // $redirect_to = 'https://www.test.com/checkout/order-pay/1234/?key=wc_order_nBSBH9A6wuFnk';
            $redirect_to = 'https://gomeek.net/api/payerurl/success';
            $notify_url = 'https://gomeek.net/api/payerurl/success';
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



        }
        else {

            if ($txnInfo->type == TxnType::Investment){
                notify()->success('Successfully Investment Apply', 'success');
                return redirect()->route('user.invest-logs');
            }else
            {
                $symbol = setting('currency_symbol','global');

                $notify = [
                    'card-header' => 'Success Your Deposit Process',
                    'title' => $symbol.$txnInfo->amount. ' Deposit Pending',
                    'p' => "The amount has been Pending added into your account",
                    'strong' => 'Transaction ID: '.$txn,
                    'action' => route('user.deposit.amount'),
                    'a' => 'Deposit again',
                    'view_name' => 'deposit'
                ];
                Session::put('user_notify',$notify);


                $shortcodes = [
                    '[[full_name]]' => $txnInfo->user->full_name,
                    '[[txn]]' => $txnInfo->tnx,
                    '[[gateway_name]]' => $gateway,
                    '[[deposit_amount]]' => $txnInfo->amount,
                    '[[site_title]]' => setting('site_title','global'),
                    '[[site_url]]' => route('home'),
                    '[[message]]' => '',
                    '[[status]]' => 'Pending',
                ];

                $this->mailSendWithTemplate(setting('site_email','global'),'manual_deposit_request',$shortcodes);


                $this->mailSendWithTemplate($txnInfo->user->email, 'user_manual_deposit_request', array_merge($shortcodes));

                return redirect()->route('user.notify');

            }

        }

        return view($data['view'], compact('txn', 'data'));

    }
}
