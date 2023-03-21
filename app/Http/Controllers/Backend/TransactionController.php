<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->middleware('permission:transaction-list');

    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|JsonResponse
     * @throws \Exception
     */
    public function transactions(Request $request, $id = null,)
    {
        if ($request->ajax()) {

            if ($id) {
                $data = Transaction::where('user_id', $id)->latest();
            } else {
                $data = Transaction::query()->latest();
            }

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

        return view('backend.transaction.index');
    }
}
