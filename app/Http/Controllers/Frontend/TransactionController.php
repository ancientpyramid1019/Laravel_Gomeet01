<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DataTables;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transactions(Request $request)
    {

        if ($request->ajax()) {
            $data = Transaction::where('user_id', \Auth::id())->orderByDesc('created_at');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('description', 'frontend.user.include.__txn_description')
                ->editColumn('status', 'frontend.user.include.__txn_status')
                ->editColumn('type', 'frontend.user.include.__txn_type')
                ->editColumn('amount', 'frontend.user.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return  $request->charge .' '.setting('site_currency', 'global');
                })
                ->rawColumns(['status', 'type', 'amount', 'description'])
                ->make(true);
        }

        return view('frontend.user.transaction.index');
    }
}
