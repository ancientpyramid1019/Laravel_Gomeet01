<?php

namespace App\Facades\Txn;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Str;


class Txn
{
    /**
     * @param  $amount
     * @param $charge
     * @param $final_amount
     * @param $method
     * @param $description
     * @param string|TxnType $type
     * @param string|TxnStatus $status
     * @param null $payCurrency
     * @param null $payAmount
     * @param null $userID
     * @param null $fromUserID
     * @param string $fromModel
     * @param array $manualDepositData
     * @param string $approvalCause
     * @return Transaction
     */
    public function new($amount, $charge, $final_amount, $method, $description, string|TxnType $type, string|TxnStatus $status = TxnStatus::Pending, $payCurrency = null, $payAmount = null, $userID = null, $relatedUserID = null, $relatedModel = 'User', array $manualFieldData = array(), string $approvalCause = 'none', $targetId = null, $targetType = null): Transaction
    {
        if ($type == 'withdraw') {
            self::withdrawBalance($amount);
        }


        $transaction = new Transaction();
        $transaction->user_id = $userID ?? Auth::user()->id;
        $transaction->from_user_id = $relatedUserID;
        $transaction->from_model = $relatedModel;
        $transaction->tnx = 'TRX' . strtoupper(Str::random(10));
        $transaction->description = $description;
        $transaction->amount = $amount;
        $transaction->type = $type;
        $transaction->charge = $charge;
        $transaction->final_amount = $final_amount;
        $transaction->method = $method;
        $transaction->pay_currency = $payCurrency;
        $transaction->pay_amount = $payAmount;
        $transaction->manual_field_data = json_encode($manualFieldData);
        $transaction->approval_cause = $approvalCause;
        $transaction->target_id = $targetId;
        $transaction->target_type = $targetType;
        $transaction->status = $status;
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $walletName
     * @param $amount
     * @return void
     */
    private function withdrawBalance($amount): void
    {
        User::find(auth()->user()->id)->removeMoney($amount);
    }

    public function update($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $transaction = Transaction::tnx($tnx);

        $uId = $userId == null ? auth()->user()->id : $userId;

        $user = User::find($uId);


        if ($status == 'success' && ($transaction->type == TxnType::Deposit || $transaction->type == TxnType::ManualDeposit)) {
            $amount = $transaction->amount;
            $user->increment('balance', $amount);

        }

        $data = [
            'status' => $status,
            'approval_cause' => $approvalCause,
        ];


        return $transaction->update($data);


    }
}
