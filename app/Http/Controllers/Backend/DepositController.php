<?php

namespace App\Http\Controllers\Backend;

use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Invest;
use App\Models\Transaction;
use App\Traits\MailSendTrait;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Txn;

class DepositController extends Controller
{

    use MailSendTrait;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:deposit-list|deposit-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:deposit-action', ['only' => ['depositAction', 'actionNow']]);
    }

    public function pending(Request $request)
    {


        if ($request->ajax()) {
            $data = Transaction::where('status', 'pending')->where(function ($query) {
                return $query->where('type', TxnType::ManualDeposit)
                    ->orWhere('type', TxnType::Investment);
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
                ->addColumn('action', 'backend.deposit.include.__action')
                ->rawColumns(['action', 'status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.deposit.manual');
    }

    public function history(Request $request)
    {

        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::ManualDeposit)
                    ->orWhere('type', TxnType::Deposit);
            })->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->rawColumns(['status', 'type', 'final_amount', 'username'])
                ->make(true);
        }

        return view('backend.deposit.history');
    }

    public function depositAction($id)
    {

        $data = Transaction::find($id)->manual_field_data;

        return view('backend.deposit.include.__deposit_action', compact('data', 'id'))->render();
    }

    public function actionNow(Request $request)
    {

        $input = $request->all();
        $id = $input['id'];
        $approvalCause = $input['message'];
        $transaction = Transaction::find($id);


        if (isset($input['approve'])) {

            if ($transaction->type == TxnType::Investment) {
                $invest = Invest::where('transaction_id', $id)->first();
                $periodHours = $invest->period_hours;
                $nextProfitTime = Carbon::now()->addHour($periodHours);
                $invest->update([
                    'next_profit_time' => $nextProfitTime,
                    'status' => InvestStatus::Ongoing,
                ]);
            } else {
                $transaction->user->increment('balance', $transaction->amount);
            }

            Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);

            notify()->success('Approve successfully');

        } elseif (isset($input['reject'])) {
            $invest = Invest::where('transaction_id', $id)->first();

            if ($invest) {
                $invest->delete();
            }
            Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
            notify()->success('Reject successfully');
        }


        $shortcodes = [
            '[[full_name]]' => $transaction->user->full_name,
            '[[txn]]' => $transaction->tnx,
            '[[gateway_name]]' => $transaction->method,
            '[[deposit_amount]]' => $transaction->amount,
            '[[site_title]]' => setting('site_title','global'),
            '[[site_url]]' => route('home'),
            '[[message]]' => $transaction->approval_cause,
            '[[status]]' => isset($input['approve']) ? 'approved': 'Rejected',
        ];


        $this->mailSendWithTemplate($transaction->user->email, 'user_manual_deposit_request', array_merge($shortcodes));

        return redirect()->back();
    }
}
