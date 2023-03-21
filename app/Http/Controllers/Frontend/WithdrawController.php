<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Txn;
use Validator;

class WithdrawController extends Controller
{

    use ImageUpload, MailSendTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::where('user_id', auth()->id())->get();

        return view('frontend.withdraw.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();


        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value']);
            }
        }


        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        WithdrawAccount::create($data);


        notify()->success('Successfully Withdraw Account Created', 'success');
        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status',true)->get();
        return view('frontend.withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::all();
        $withdrawAccount = WithdrawAccount::find($id);
        return view('frontend.withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();


        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);


        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (!isset($value['value'])) {
                $credentials[$key]['value'] = $oldCredentials[$key]['value'];
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        $withdrawAccount->update($data);
        notify()->success('Successfully Withdraw Account Updated', 'success');
        return redirect()->route('user.withdraw.account.index');

    }

    /**
     * @return Application|Factory|View
     */
    public function withdraw()
    {

        $accounts = WithdrawAccount::where('user_id', \Auth::id())->get();
        $accounts = $accounts->reject(function ($value, $key) {
            return !$value->method->status;
        });


        return view('frontend.withdraw.now', compact('accounts'));
    }

    /**
     * @param $id
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend.withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }


    /**
     * @param $accountId
     * @param int $amount
     * @return array
     */
    public function details($accountId, int $amount = 0)
    {

        $withdrawAccount = WithdrawAccount::find($accountId);

        $credentials = json_decode($withdrawAccount->credentials, true);

        $currency = setting('site_currency', 'global');
        $method = $withdrawAccount->method;
        $charge = $method->charge;
        $name = $withdrawAccount->method_name;

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'range' => 'Minimum ' . $method->min_withdraw . ' ' . $currency . ' and ' . 'Maximum ' . $method->max_withdraw . ' ' . $currency,
            'processing_time' => 'Processing Time: ' . $withdrawAccount->method->required_time . $withdrawAccount->method->required_time_format,
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }

        $html = view('frontend.withdraw.include.__details', compact('credentials', 'name', 'charge'))->render();


        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function withdrawNow(Request $request)
    {

        if (!setting('user_withdraw', 'permission') || !\Auth::user()->withdraw_status ) {
            abort('403',__('Withdraw Disable Now'));
        }

        $withdrawOffDays = WithdrawalSchedule::where('status',0)->pluck('name')->toArray();
        $date = Carbon::now();
        $today = $date->format('l');

        if (in_array($today, $withdrawOffDays) ) {
            abort('403',__('Today is the off day of withdraw'));
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
            'withdraw_account' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }








        $input = $request->all();


        $amount = (double)$input['amount'];

        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;

        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Withdraw the Amount within the range ' . $currencySymbol . $withdrawMethod->min_withdraw . ' to ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');
            return redirect()->back();
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;

        $totalAmount = $amount + (double)$charge;

        $user = Auth::user();

        if ($user->balance < $totalAmount) {
            notify()->error(__('Insufficient Balance Your Main Wallet'), 'Error');
            return redirect()->back();
        }

        $user->decrement('balance', $totalAmount);

        $payAmount = $amount * $withdrawMethod->rate;

        $txnInfo = Txn::new($input['amount'], $charge, $totalAmount, $withdrawMethod->name,
            'Withdraw With ' . $withdrawAccount->method_name, TxnType::Withdraw,
            TxnStatus::Pending, $withdrawMethod->currency, $payAmount, $user->id, null, 'User', json_decode($withdrawAccount->credentials, true));


        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => 'Withdraw Money',
            'title' => $symbol . $txnInfo->amount . ' Withdraw Request Successful',
            'p' => "The Withdraw Request has been successfully sent",
            'strong' => 'Transaction ID: ' . $txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => 'WITHDRAW REQUEST AGAIN',
            'view_name' => 'withdraw'
        ];
        Session::put('user_notify',$notify);

        $shortcodes = [
            '[[full_name]]' => $txnInfo->user->full_name,
            '[[txn]]' => $txnInfo->tnx,
            '[[method_name]]' => $withdrawMethod->name,
            '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailSendWithTemplate(setting('site_email', 'global'), 'withdraw_request', $shortcodes);

        $this->mailSendWithTemplate($user->email, 'withdraw_request_user', array_merge($shortcodes,[
            '[[message]]' => '',
            '[[status]]' => 'Pending',
        ]));
        return redirect()->route('user.notify');


    }

    public function withdrawLog(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('user_id', \Auth::id())
                    ->where('type', TxnType::Withdraw);
            })->orderByDesc('created_at');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('description', 'frontend.user.include.__txn_description')
                ->editColumn('status', 'frontend.user.include.__txn_status')
                ->editColumn('type', 'frontend.user.include.__txn_type')
                ->editColumn('amount', 'frontend.user.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge == 0 ? 'NA' : setting('currency_symbol', 'global') . $request->charge;
                })
                ->rawColumns(['description', 'status', 'type', 'amount'])
                ->make(true);
        }

        return view('frontend.withdraw.log');
    }
}
