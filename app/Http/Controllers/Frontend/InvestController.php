<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Gateway;
use App\Models\Invest;
use App\Models\Schema;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use Auth;
use Carbon\Carbon;
use charlesassets\LaravelPerfectMoney\PerfectMoney;
use Crypt;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mollie\Laravel\Facades\Mollie;
use Session;
use Shakurov\Coinbase\Facades\Coinbase;
use Txn;
use Unicodeveloper\Paystack\Facades\Paystack;

class InvestController extends GatewayController
{
    use ImageUpload, MailSendTrait;

    public function investNow(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'schema_id' => 'required',
            'invest_amount' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'wallet' => 'in:main,profit,gateway',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $user = Auth::user();
        $schema = Schema::with('schedule')->find($input['schema_id']);

        $investAmount = $input['invest_amount'];

        //Insufficient Balance validation
        if ($input['wallet'] == 'main' && $user->balance < $investAmount) {
            notify()->error('Insufficient Balance Your Main Wallet', 'Error');
            return redirect()->route('user.schema.preview', $schema->id);
        } elseif ($input['wallet'] == 'profit' && $user->profit_balance < $investAmount) {
            notify()->error('Insufficient Balance Your Profit Wallet', 'Error');
            return redirect()->route('user.schema.preview', $schema->id);
        }

        //invalid Amount
        if (($schema->type == 'range' && ($schema->min_amount > $investAmount || $schema->max_amount < $investAmount)) ||
            ($schema->type == 'fixed' && $schema->fixed_amount <> $investAmount)) {
            notify()->error('Invest Amount Out Of Range', 'Error');
            return redirect()->route('user.schema.preview', $schema->id);
        }


        $periodHours = $schema->schedule->time;
        $nextProfitTime = Carbon::now()->addHour($periodHours);
        $siteName = setting('site_title', 'global');
        $data = [
            'user_id' => $user->id,
            'schema_id' => $schema->id,
            'invest_amount' => $investAmount,
            'next_profit_time' => $nextProfitTime,
            'capital_back' => $schema->capital_back,
            'interest' => $schema->return_interest,
            'interest_type' => $schema->interest_type,
            'return_type' => $schema->return_type,
            'number_of_period' => $schema->number_of_period,
            'period_hours' => $periodHours,
            'wallet' => $input['wallet'],
            'status' => InvestStatus::Ongoing,
        ];

        if ($input['wallet'] == 'main') {
            $user->decrement('balance', $input['invest_amount']);

        } elseif ($input['wallet'] == 'profit') {
            $user->decrement('profit_balance', $input['invest_amount']);
        } else {

            $gatewayInfo = Gateway::code($input['gateway_code'])->first();

            $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $investAmount) : $gatewayInfo->charge;
            $finalAmount = (double)$investAmount + (double)$charge;
            $payAmount = $finalAmount * $gatewayInfo->rate;
            $payCurrency = $gatewayInfo->currency;


            $manualData = null;
            if (isset($input['manual_data'])) {


                $manualData = $input['manual_data'];


                foreach ($manualData as $key => $value) {

                    if (is_file($value)) {
                        $manualData[$key] = self::imageUploadTrait($value);
                    }
                }

            }

            $txnInfo = Txn::new($investAmount, $charge, $finalAmount, $gatewayInfo->name, $schema->name . ' Invested', TxnType::Investment, TxnStatus::Pending, $payCurrency, $payAmount, $user->id, null, 'user', $manualData ?? []);

            $data = array_merge($data, ['status' => InvestStatus::Pending, 'transaction_id' => $txnInfo->id]);

            Invest::create($data);

            return self::directGateway($input['gateway_code'], $txnInfo);

        }

        $tnxInfo = Txn::new($input['invest_amount'], 0, $input['invest_amount'], 'system', $schema->name . ' Plan Invested', TxnType::Investment, TxnStatus::Success, null, null, $user->id);
        $data = array_merge($data, ['transaction_id' => $tnxInfo->id]);
        Invest::create($data);


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



    public function investLogs(Request $request)
    {

<<<<<<< HEAD
=======

>>>>>>> a8543ca (change style sun to the moon)
        if ($request->ajax()) {

            $data = Invest::with('schema')->where('user_id', auth()->id())->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', 'frontend.user.include.__invest_icon')
                ->addColumn('schema', 'frontend.user.include.__invest_schema')
                ->addColumn('rio', 'frontend.user.include.__invest_rio')
                ->addColumn('profit', 'frontend.user.include.__invest_profit')
<<<<<<< HEAD
                ->addColumn('period_remaining', function ($raw) {
                    
=======
                ->addColumn('period_remaining', function ($raw){
>>>>>>> a8543ca (change style sun to the moon)
                    if ($raw->return_type != 'period'){
                        return 'Unlimited';
                    }
                    return $raw->number_of_period.($raw->number_of_period < 2 ?' Time' : ' Times');
                })
                ->editColumn('capital_back', 'frontend.user.include.__invest_capital_back')
                ->editColumn('next_profit_time', 'frontend.user.include.__invest_next_profit_time')
                ->rawColumns(['icon', 'schema', 'rio', 'profit', 'capital_back', 'next_profit_time'])
                ->make(true);
        }
        return view('frontend.user.invest.log');
    }
}
