<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\MailSendTrait;
use Illuminate\Http\Request;
use Session;
use Txn;

class StatusController extends Controller
{
    use MailSendTrait;

    public function success()
    {

        $depositTnx = Session::get('deposit_tnx');

        $tnxInfo = Transaction::tnx($depositTnx);

        if ($tnxInfo->type == TxnType::Investment) {


            $shortcodes = [
                '[[full_name]]' => $tnxInfo->user->full_name,
                '[[txn]]' => $tnxInfo->tnx,
                '[[plan_name]]' => $tnxInfo->invest->schema->name,
                '[[invest_amount]]' => $tnxInfo->amount . setting('site_currency', 'global'),
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $this->mailSendWithTemplate($tnxInfo->user->email, 'user_investment', $shortcodes);

            notify()->success('Successfully Investment', 'success');
            return redirect()->route('user.invest-logs');
        }

        $symbol = setting('currency_symbol', 'global');


        $notify = [
            'card-header' => 'Success Your Deposit',
            'title' => $symbol . $tnxInfo->amount . ' Deposit Successfully',
            'p' => "The amount has been successfully added into your account",
            'strong' => 'Transaction ID: ' . $depositTnx,
            'action' => route('user.deposit.amount'),
            'a' => 'Deposit again',
            'view' => 'user'
        ];

        $isStepOne = 'current';
        $isStepTwo = 'current';


        return view('frontend.deposit.success', compact('isStepOne', 'isStepTwo', 'notify'));

    }


    public function cancel(Request $request)
    {
        $trx = Session::get('deposit_tnx');
        Txn::update($trx, 'failed');

        notify()->warning('Payment Canceled');
        return redirect(route('user.dashboard'));
    }
}
