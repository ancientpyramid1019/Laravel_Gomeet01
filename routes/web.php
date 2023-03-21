<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\DepositController;
use App\Http\Controllers\Frontend\GatewayController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\InvestController;
use App\Http\Controllers\Frontend\IpnController;
use App\Http\Controllers\Frontend\KycController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ReferralController;
use App\Http\Controllers\Frontend\SchemaController;
use App\Http\Controllers\Frontend\SendMoneyController;
use App\Http\Controllers\Frontend\SettingController;
use App\Http\Controllers\Frontend\StatusController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\Frontend\TransactionController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [HomeController::class, 'home'])->name('home');
Route::post('subscriber', [HomeController::class, 'subscribeNow'])->name('subscriber');

//Static Page
Route::get('/{page}', PageController::class)->name('page')->where('page', 'schema|how-it-works|about-us|faq|rankings|blog|contact|privacy-policy|terms-and-conditions');

//Dynamic Page
Route::get('page/{section}', [PageController::class, 'getPage'])->name('dynamic.page');

Route::get('blog/{id}', [PageController::class, 'blogDetails'])->name('blog-details');
Route::post('mail-send', [PageController::class, 'mailSend'])->name('mail-send');


// ======================================  User Part =======================================================
Route::group(['middleware' => ['auth', '2fa', 'isActive', 'translate', setting('email_verification', 'permission') ? 'verified' : 'web'], 'prefix' => 'user', 'as' => 'user.'], function () {
    //dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    //user notify
    Route::get('notify', [UserController::class, 'notifyUser'])->name('notify');

    //change Password
    Route::get('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::post('/password-store', [UserController::class, 'newPassword'])->name('new.password');

    //kyc apply
    Route::get('kyc', [KycController::class, 'kyc'])->name('kyc');
    Route::get('kyc/{id}', [KycController::class, 'kycData'])->name('kyc.data');
    Route::post('kyc-submit', [KycController::class, 'submit'])->name('kyc.submit');

    Route::get('schemas', [SchemaController::class, 'index'])->name('schema');
    Route::get('schema-preview/{id}', [SchemaController::class, 'schemaPreview'])->name('schema.preview');


    Route::post('invest-now', [InvestController::class, 'investNow'])->name('invest-now');
    Route::get('invest-logs', [InvestController::class, 'investLogs'])->name('invest-logs');
    Route::get('transactions', [TransactionController::class, 'transactions'])->name('transactions');

    // Deposit
    Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
        Route::get('', [DepositController::class, 'deposit'])->name('amount');
        Route::get('gateway/{code}', [GatewayController::class, 'gateway'])->name('gateway');
        Route::post('now', [DepositController::class, 'depositNow'])->name('now');
        Route::get('log', [DepositController::class, 'depositLog'])->name('log');
    });
    //Send Money
    Route::group(['prefix' => 'send-money', 'as' => 'send-money.', 'controller' => SendMoneyController::class], function () {
        Route::get('/', 'sendMoney')->name('view');
        Route::post('now', 'sendMoneyNow')->name('now');
        Route::get('log', 'sendMoneyLog')->name('log');

    });

    //wallet exchange
    Route::get('wallet-exchange', [UserController::class, 'walletExchange'])->name('wallet-exchange');
    Route::post('wallet-exchange-now', [UserController::class, 'walletExchangeNow'])->name('wallet-exchange-now');

    //withdraw
    Route::group(['middleware' => 'KYC', 'prefix' => 'withdraw', 'as' => 'withdraw.', 'controller' => WithdrawController::class], function () {
        //withdraw methods
        Route::resource('account', WithdrawController::class)->except('show');
        //user withdraw
        Route::get('/', 'withdraw')->name('view');
        Route::get('details/{accountId}/{amount?}', 'details')->name('details');
        Route::get('method/{id}', 'withdrawMethod')->name('method');
        Route::post('now', 'withdrawNow')->name('now');
        Route::get('log', 'withdrawLog')->name('log');

    });

    Route::get('exist/{email}', [UserController::class, 'userExist'])->name('exist');


    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.', 'controller' => TicketController::class], function () {
        Route::get('index', 'index')->name('index');
        Route::get('new', 'new')->name('new');
        Route::post('new-store', 'store')->name('new-store');
        Route::post('reply', 'reply')->name('reply');
        Route::get('show/{uuid}', 'show')->name('show');
        Route::get('close-now/{uuid}', 'closeNow')->name('close.now');
    });

    Route::get('referral', [ReferralController::class, 'referral'])->name('referral');

    Route::get('ranking-badge', [UserController::class, 'rankingBadge'])->name('ranking-badge');


    //settings
    Route::group(['prefix' => 'settings', 'as' => 'setting.', 'controller' => SettingController::class], function () {
        Route::get('/', 'settings')->name('show');
        Route::get('2fa', 'twoFa')->name('2fa');
        Route::post('action-2fa', 'actionTwoFa')->name('action-2fa');
        Route::post('profile-update', 'profileUpdate')->name('profile-update');

        Route::post('/2fa/verify', function () {
            return redirect(route('user.dashboard'));
        })->name('2fa.verify');

    });

});



Route::group(['middleware' => ['auth', 'XSS', 'translate']], function () {
//====================  Gateway Payment ===========================
    Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'controller' => GatewayController::class], function () {
        //paypal
        Route::post('/paypal', 'paypalGateway')->name('paypal');
        Route::get('/paypal-success', 'paypalSuccess')->name('paypal.success');
        Route::get('/paypal-cancel', 'paypalCancel')->name('paypal.cancel');

        //stripe------------------>
        Route::get('stripe', 'stripeGateway')->name('stripe');

        //mollie
        Route::get('mollie', 'mollieGateway')->name('mollie');
        Route::post('webhooks/mollie', 'mollieHandleWebhookNotification')->name('webhooks.mollie');

        //perfect money
        Route::any('perfectMoney', 'perfectMoney')->name('perfectMoney');

        //perfect money
        Route::get('coinbase', 'coinbase')->name('coinbase');

        //paystack

        Route::get('paystack/callback', 'paystackCallback')->name('paystack.callback');
        //voguepay
        Route::get('voguepay/success', 'voguepaySuccess')->name('voguepay.success');

        //flutterwave
        Route::get('flutterwave/callback', 'flutterwaveProcess')->name('flutterwave.callback');

        //coingate
        Route::post('coingate/callback', 'coingateProcess')->name('coingate.callback')->withoutMiddleware('auth');
        Route::get('coingate-success', 'coingateSuccess')->name('coingate.success');
        Route::get('coingate-cancel', 'coingateCancel')->name('coingate.cancel');

        //monnify
        Route::get('monnify/callback', 'monnifyCallback')->name('monnify.callback');

        //monnify
        Route::post('securion-pay', 'securionPayNow')->name('securion.pay');
        
        //payerurl
        // Route::post('/payerurl-payment', 'payerurlSuccess')->name('payerurl');
        // Route::get('/payerurl-success', 'payerurlSuccess')->name('payerurl.success');
        // Route::get('/payerurl-cancel', 'paypalCancel')->name('payerurl.cancel');

        //list
        Route::get('list', 'gatewayList')->name('list');
    });

//====================  Gateway Status ===========================
    Route::group(['controller' => StatusController::class, 'prefix' => 'status', 'as' => 'status.'], function () {
        Route::get('/success', 'success')->name('success');
        Route::match(['get', 'post'], '/cancel', 'cancel')->name('cancel');
    });



//translate
    Route::get('language-update', [HomeController::class, 'languageUpdate'])->name('language-update');

});

//ipn setup

Route::group(['prefix' => 'ipn','as' => 'ipn.','controller' => IpnController::class],function (){
    Route::post('coinpayments','coinpaymentsIpn')->name('coinpayments');

});

//site others
Route::get('theme-mode', [HomeController::class, 'themeMode'])->name('mode-theme');

//schema without auth
Route::get('schema-select/{id}', [SchemaController::class, 'schemaSelect'])->name('user.schema.select');


//site cron job
Route::get('cron-job/investment', [CronJobController::class, 'investmentCronJob'])->name('cron-job.investment');
Route::get('cron-job/referral', [CronJobController::class, 'referralCronJob'])->name('cron-job.referral');
Route::get('cron-job/user-ranking', [CronJobController::class, 'userRanking'])->name('cron-job.user-ranking');


