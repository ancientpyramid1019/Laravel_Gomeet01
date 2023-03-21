<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Hash;
use Illuminate\Http\Request;
use Session;
use Txn;
use Validator;

class UserController extends Controller
{
    public function userExist($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $data = 'Name: ' . $user->first_name . ' ' . $user->last_name;
        } else {
            $data = 'User Not Found';
        }

        return $data;
    }

    public function changePassword()
    {
        return view('frontend.user.change_password');
    }


    public function newPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        notify()->success('Password Changed Successfully');
        return redirect()->back();

    }

    public function rankingBadge()
    {
        $alreadyRank = json_decode(auth()->user()->rankings, true);

        $rankings = Ranking::where('status', true)->get();

        return view('frontend.ranking.index', compact('rankings', 'alreadyRank'));
    }

    public function walletExchange()
    {
        $isStepOne = 'current';
        $isStepTwo = '';
        return view('frontend.wallet.now', compact('isStepOne', 'isStepTwo'));
    }

    public function walletExchangeNow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_wallet' => ['required', 'different:to_wallet'],
            'to_wallet' => ['required', 'different:from_wallet'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }


        $input = $request->all();


        $amount = (double)$input['amount'];
        $chargeType = Setting('wallet_exchange_charge_type', 'fee');

        $charge = (double)Setting('wallet_exchange_charge', 'fee');

        if ($chargeType == 'percentage') {
            $charge = $amount * ($charge / 100);
        }

        $totalAmount = $amount + $charge;

        $user = \Auth::user();

        if (1 == $input['from_wallet'] && $user->balance < $totalAmount || 2 == $input['from_wallet'] && $user->profit_balance < $totalAmount) {

            $walletName = 1 == $input['from_wallet'] ? 'Main Wallet' : 'Profit Wallet';

            notify()->error(__('Insufficient Balance Your ') . $walletName, 'Error');
            return redirect()->back();
        }


        if (1 == $input['from_wallet']) {

            $user->decrement('balance', $totalAmount);
            $user->increment('profit_balance', $amount);

            $sendDescription = 'Main to Profit Wallet Exchanged';
            $txnInfo = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription,
                TxnType::Exchange, TxnStatus::Success, null, null, $user->id);

        } else {

            $user->decrement('profit_balance', $totalAmount);
            $user->increment('balance', $amount);


            $sendDescription = 'Profit to Main Wallet Exchanged';
            $txnInfo = Txn::new($amount, $charge, $totalAmount, 'system', $sendDescription,
                TxnType::Exchange, TxnStatus::Success, null, null, $user->id);
        }


        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => 'Success Your Exchange Money Process',
            'title' => $symbol . $txnInfo->amount . ' Exchange Wallet Money Successfully',
            'p' => $sendDescription,
            'strong' => 'Transaction ID: ' . $txnInfo->tnx,
            'action' => route('user.wallet-exchange'),
            'a' => 'Exchange Wallet Money again',
            'view_name' => 'wallet',
        ];
        Session::put('user_notify',$notify);
        return redirect()->route('user.notify');

    }

    public function notifyUser()
    {
        $notify = Session::get('user_notify');
        $isStepOne = 'current';
        $isStepTwo = 'current';
        $viewName = $notify['view_name'];

        return view('frontend.'.$viewName.'.success',compact('isStepOne','isStepTwo','notify'));
    }
}
