<?php

namespace App\Listeners;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Events\UserReferred;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use Txn;

class RewardUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\UserReferred $event
     * @return void
     */
    public function handle(UserReferred $event)
    {
        $referral = ReferralLink::find($event->referralId);
        if (!is_null($referral)) {
            ReferralRelationship::create(['referral_link_id' => $referral->id, 'user_id' => $event->user->id]);


            // Sign Up Referral Bonus
            if (setting('sign_up_referral', 'permission')) {

                $referralBonus = (double)setting('referral_bonus', 'fee');

                // User who was sharing link
                $provider = $referral->user;
                $provider->increment('profit_balance', $referralBonus);
                Txn::new($referralBonus, 0, $referralBonus, 'system', 'Referral Bonus via ' . $event->user->full_name, TxnType::Referral, TxnStatus::Success, null, null, $provider->id);


            }

        }
    }
}
