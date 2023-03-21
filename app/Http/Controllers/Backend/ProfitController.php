<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->middleware('permission:profit-list');

    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function allProfits(Request $request, $id = null)
    {

        if ($request->ajax()) {
            if ($id) {
                $data = Transaction::where('user_id', $id)->whereIn('type', [
                    TxnType::Referral,
                    TxnType::Interest,
                    TxnType::Bonus,
                    TxnType::SignupBonus,
                ])->latest();
            } else {
                $data = Transaction::whereIn('type', [
                    TxnType::Referral,
                    TxnType::Interest,
                    TxnType::Bonus,
                    TxnType::SignupBonus,
                ])->latest();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('profit_from', function ($request) {
                    return $request->from_user_id != null ? User::find($request->from_user_id)->username : 'System';
                })
                ->rawColumns(['status', 'type', 'final_amount', 'username'])
                ->make(true);
        }

        return view('backend.transaction.profit');
    }
}
