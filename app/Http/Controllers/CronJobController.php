<?php

namespace App\Http\Controllers;

use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Invest;
use App\Models\Ranking;
use App\Models\Referral;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\Schema;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\MailSendTrait;
use Carbon\Carbon;
use Txn;

class CronJobController extends Controller
{
    use MailSendTrait;
    /**
     * @return string
     */
    public function investmentCronJob()
    {


        $ongoingInvestment = Invest::where('status', InvestStatus::Ongoing)->where('next_profit_time', '<=', Carbon::now()->format('Y-m-d H:i:s'))->cursor();

        foreach ($ongoingInvestment as $invest) {
            $schema = Schema::find($invest->schema_id);
            $user = User::find($invest->user_id);
            $calculateInterest = ($invest->interest * $invest->invest_amount) / 100;
            $interest = $invest->interest_type != 'percentage' ? $invest->interest : $calculateInterest;

            $nextProfitTime = Carbon::now()->addHour($invest->period_hours);


            $updateData = [
                'next_profit_time' => $nextProfitTime,
                'last_profit_time' => Carbon::now(),
                'number_of_period' => ($invest->number_of_period - 1),
                'already_return_profit' => ($invest->already_return_profit + 1),
                'total_profit_amount' => ($invest->total_profit_amount + $interest),
            ];

            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[plan_name]]' => $schema->name,
                '[[invest_amount]]' => $invest->invest_amount,
                '[[roi]]' => $interest,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];



            if ($invest->return_type == 'lifetime') {
                $invest->update($updateData);

                $user->increment('profit_balance', $interest);
                $tnxInfo = Txn::new($interest, 0, $interest, 'system', $schema->name . ' Plan Interest', TxnType::Interest, TxnStatus::Success, null,null,$user->id);
                //email newsletter
                $this->mailSendWithTemplate($tnxInfo->user->email, 'invest_roi', array_merge($shortcodes,['[[txn]]' => $tnxInfo->tnx]));

            } else {

                if ($invest->number_of_period > 0) {
                    if ($invest->number_of_period == 1) {
                        $updateData = array_merge($updateData, [
                            'status' => InvestStatus::Completed
                        ]);

                        if ($invest->capital_back == 1) {
                            $user->increment('balance', $invest->invest_amount);
                            $tnxInfo =  Txn::new($invest->invest_amount, 0, $invest->invest_amount, 'system', $schema->name . ' Capital Back', TxnType::Refund, TxnStatus::Success,null,null, $user->id);

                            //email newsletter
                            $this->mailSendWithTemplate($tnxInfo->user->email, 'investment_end', array_merge($shortcodes,['[[roi]]' => '']));
                        }

                    }

                    $invest->update($updateData);

                    $user->increment('profit_balance', $interest);
                    $tnxInfo = Txn::new($interest, 0, $interest, 'system', $schema->name . ' Plan Interest', TxnType::Interest, TxnStatus::Success,null,null, $user->id);
                    //email newsletter
                    $this->mailSendWithTemplate($tnxInfo->user->email, 'invest_roi', array_merge($shortcodes,['[[txn]]' => $tnxInfo->tnx]));
                }

            }
        }

        return '....cron job successfully completed';
    }

    /**
     * @return string
     */
    public function referralCronJob()
    {
        $referrals = Referral::all();
        $referralRelationship = ReferralRelationship::all();

        foreach ($referralRelationship as $relationship) {
            $provider = ReferralLink::find($relationship->referral_link_id)->user;

            $user = User::find($relationship->user_id);


            $totalDeposit = $user->totalDeposit();
            $totalInvest = $user->totalInvestment();


            $filterReferrals = $referrals->reject(function ($referral) use ($provider, $user) {

                return Transaction::where(function ($query) use ($user, $provider, $referral) {
                    $query->where('target_id', '!=', null)
                        ->where('user_id', $provider->id)
                        ->where('from_user_id', $user->id)
                        ->where('target_id', $referral->referral_target_id)
                        ->where('target_type', $referral->type);

                })->exists();

            })->map(function ($referral) {
                return $referral;
            });


            foreach ($filterReferrals as $referral) {

                $referralBonus = ($referral->bounty * $referral->target_amount) / 100;

                $targetName = $referral->target->name;


                if ($referral->type == 'deposit' && $referral->target_amount <= $totalDeposit && setting('deposit_referral_bounty', 'permission')) {
                    Txn::new($referralBonus, 0, $referralBonus, 'system', 'Referral Bonus with ' . $targetName . ' Via ' . $user->full_name, TxnType::Referral, TxnStatus::Success, null, null, $provider->id, $user->id, 'User', [], 'none', $referral->referral_target_id, $referral->type);
                    $provider->increment('profit_balance', $referralBonus);
                }

                if ($referral->type == 'investment' && $referral->target_amount <= $totalInvest && setting('investment_referral_bounty', 'permission')) {
                    Txn::new($referralBonus, 0, $referralBonus, 'system', 'Referral Bonus with ' . $targetName . ' Via ' . $user->full_name, TxnType::Referral, TxnStatus::Success, null, null, $provider->id, $user->id, 'User', [], 'none', $referral->referral_target_id, $referral->type);
                    $provider->increment('profit_balance', $referralBonus);
                }

            }


        }

        return '....referral job successfully completed';

    }


    /**
     * @return string
     */
    public function userRanking()
    {
        $rank = Ranking::where('status', true)->get(['id', 'bonus', 'minimum_earnings']);

        User::chunk(200, function ($users) use ($rank) {
            foreach ($users as $user) {
                $totalEarning = $user->totalProfit();

                $ranks = $rank->whereNotIn('id', json_decode($user->rankings))->where('minimum_earnings', '<=', $totalEarning);
                $maxRank = $ranks->max('minimum_earnings');

                foreach ($ranks as $rank) {


                    Txn::new($rank->bonus, 0, $rank->bonus, 'system', 'Referral Bonus by ' . $rank->ranking, TxnType::Bonus, TxnStatus::Success, null, null, $user->id);
                    $user->increment('profit_balance', $rank->bonus);

                    if ($rank->minimum_earnings == $maxRank) {
                        $oldRanks = json_decode($user->rankings);
                        $user->update([
                            'ranking_id' => $rank->id,
                            'rankings' => json_encode(array_merge($oldRanks, [$rank->id])),
                        ]);
                    }

                }


            }
        });

        return '....referral job successfully completed';

    }
}
