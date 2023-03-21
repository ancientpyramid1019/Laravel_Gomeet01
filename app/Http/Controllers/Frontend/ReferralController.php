<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class ReferralController extends Controller
{
    public function referral()
    {
        if (!setting('sign_up_referral', 'permission')) {
            // notify()->error('Referral Disabled From Admin', 'Error');
            // return redirect()->back();
            abort('404');
        }
        $user = auth()->user();

        $referrals = Transaction::where('user_id', $user->id)->where('target_type', '!=', null)->get()->groupBy('target');

        $generalReferrals = Transaction::where('user_id', $user->id)->where('target_type', null)->where('type', TxnType::Referral)->latest()->paginate(8);


        $getReferral = $user->getReferrals()->first();

        $totalReferralProfit = $user->totalReferralProfit();

        return view('frontend.referral.index', compact('referrals', 'getReferral', 'totalReferralProfit', 'generalReferrals'));
    }
}
