<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Traits\ImageUpload;
use App\Traits\MailSendTrait;
use DataTables;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
use Txn;

class WithdrawController extends Controller
{
    use ImageUpload, MailSendTrait;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:withdraw-method-manage', ['only' => ['methods', 'methodCreate', 'methodStore', 'methodEdit', 'methodUpdate']]);
        $this->middleware('permission:withdraw-list|withdraw-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:withdraw-action', ['only' => ['withdrawAction', 'actionNow']]);
        $this->middleware('permission:withdraw-schedule', ['only' => ['schedule', 'scheduleUpdate']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function methods()
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.withdraw.account-create'),
        ];
        $withdrawMethods = WithdrawMethod::all();
        return view('backend.withdraw.method', compact('withdrawMethods', 'button'));
    }

    /**
     * @return Application|Factory|View
     */
    public function methodCreate()
    {
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw.methods'),
        ];
        return view('backend.withdraw.method_create', compact('button'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function methodStore(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'icon' => 'required',
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required',
            'required_time_format' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $data = [
            'icon' => self::imageUploadTrait($input['icon']),
            'name' => $input['name'],
            'required_time' => $input['required_time'],
            'required_time_format' => $input['required_time_format'],
            'currency' => $input['currency'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        $withdrawMethod = WithdrawMethod::create($data);
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Created'));
        return redirect()->route('admin.withdraw.methods');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function methodEdit($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw.methods'),
        ];
        return view('backend.withdraw.method_edit', compact('button', 'withdrawMethod'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function methodUpdate(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required',
            'required_time_format' => 'required',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $withdrawMethod = WithdrawMethod::find($id);

        $data = [
            'name' => $input['name'],
            'required_time' => $input['required_time'],
            'required_time_format' => $input['required_time_format'],
            'currency' => $input['currency'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        if ($request->hasFile('icon')) {
            $icon = self::imageUploadTrait($input['icon'], $withdrawMethod->icon);
            $data = array_merge($data, ['icon' => $icon]);
        }

        $withdrawMethod->update($data);
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Updated'));
        return redirect()->route('admin.withdraw.methods');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function pending(Request $request)
    {

        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::Withdraw)
                    ->where('status', 'pending');
            })->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.withdraw.include.__action')
                ->rawColumns(['action', 'status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.withdraw.pending');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function history(Request $request)
    {

        $data = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw);

        })->get();


        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::Withdraw);

            })->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->rawColumns(['status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.withdraw.history');
    }

    /**
     * @param $id
     * @return string
     */
    public function withdrawAction($id)
    {

        $data = Transaction::find($id)->manual_field_data;

        return view('backend.withdraw.include.__withdraw_action', compact('data', 'id'))->render();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function actionNow(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $approvalCause = $input['message'];
        $transaction = Transaction::find($id);
        $user = User::find($transaction->user_id);

        if (isset($input['approve'])) {
            Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
            notify()->success('Approve successfully');
        } elseif (isset($input['reject'])) {

            $user->increment('balance', $transaction->final_amount);
            Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);

            $newTransaction = $transaction->replicate();
            $newTransaction->type = TxnType::Refund;
            $newTransaction->status = TxnStatus::Success;
            $newTransaction['method'] = 'system';
            $newTransaction->tnx = 'TRX' . strtoupper(Str::random(10));
            $newTransaction->save();
            notify()->success('Reject successfully');
        }


        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[txn]]' => $transaction->tnx,
            '[[method_name]]' => $transaction->method,
            '[[withdraw_amount]]' => $transaction->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[message]]' => $transaction->approval_cause,
            '[[status]]' => isset($input['approve']) ? 'approved': 'Rejected',
        ];

        $this->mailSendWithTemplate($user->email, 'withdraw_request_user', array_merge($shortcodes));

        return redirect()->back();
    }


    public function schedule()
    {
        $schedules = WithdrawalSchedule::all();

        return view('backend.withdraw.schedule',compact('schedules'));
    }
    public function scheduleUpdate(Request $request)
    {

        $updateSchedules = $request->except('_token');
        foreach ($updateSchedules as $name => $status)
        {
            WithdrawalSchedule::where('name',$name)->update([
               'status' =>  $status
            ]);
        }

        notify()->success('Withdrawal Schedule Update successfully');
        return redirect()->back();
    }
}
