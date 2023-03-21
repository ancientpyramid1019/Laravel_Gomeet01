<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use DataTables;
use Illuminate\Http\Request;
use Session;
use Txn;
use Validator;

class DepositController extends GatewayController
{

    use ImageUpload, MailSendTrait;

    public function deposit()
    {

        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status ) {
            abort('403','Deposit Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';

        $gateways = Gateway::where('status', true)->get();
        return view('frontend.deposit.now', compact('isStepOne', 'isStepTwo', 'gateways'));
    }

    public function depositNow(Request $request)
    {

        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status ) {
            abort('403','Deposit Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'gateway_code' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/']
        ]);



        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }


        $input = $request->all();

        $gatewayInfo = Gateway::code($input['gateway_code'])->first();
        $amount = $input['amount'];

        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Deposit the Amount within the range ' . $currencySymbol . $gatewayInfo->minimum_deposit . ' to ' . $currencySymbol . $gatewayInfo->maximum_deposit;
            notify()->error($message, 'Error');
            return redirect()->back();
        }


        $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
        $finalAmount = (double)$amount + (double)$charge;
        $payAmount = $finalAmount * $gatewayInfo->rate;
        $depositType = TxnType::Deposit;

        if (isset($input['manual_data'])) {

            $depositType = TxnType::ManualDeposit;
            $manualData = $input['manual_data'];

            foreach ($manualData as $key => $value) {

                if (is_file($value)) {
                    $manualData[$key] = self::imageUploadTrait($value);
                }
            }

        }




        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Deposit With ' . $gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? []);

        return self::directGateway($gatewayInfo->gateway_code, $txnInfo);



    }


    public function depositLog(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::where('user_id', \Auth::id())->where(function ($query) {
                $query->where('type', TxnType::Deposit)
                    ->orWhere('type', TxnType::ManualDeposit);
            })->orderByDesc('created_at');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('description', 'frontend.user.include.__txn_description')
                ->editColumn('status', 'frontend.user.include.__txn_status')
                ->editColumn('type', 'frontend.user.include.__txn_type')
                ->editColumn('amount', 'frontend.user.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->rawColumns(['description', 'status', 'type', 'amount'])
                ->make(true);
        }

        return view('frontend.deposit.log');
    }



}
