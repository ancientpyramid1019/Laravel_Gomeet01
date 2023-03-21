<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invest;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:investment-list');

    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function investments(Request $request, $id = null)
    {
        if ($request->ajax()) {

            if ($id) {
                $data = Invest::with('schema')->where('user_id', $id)->latest();
            } else {
                $data = Invest::query()->with('schema')->latest();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', 'backend.investment.include.__invest_icon')
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('schema', 'backend.investment.include.__invest_schema')
                ->addColumn('rio', 'backend.investment.include.__invest_rio')
                ->addColumn('profit', 'backend.investment.include.__invest_profit')
                ->addColumn('period_remaining', function ($raw){
                    if ($raw->return_type != 'period'){
                        return 'Unlimited';
                    }
                    return $raw->number_of_period.($raw->number_of_period < 2 ?' Time' : ' Times');
                })
                ->editColumn('capital_back', 'backend.investment.include.__invest_capital_back')
                ->editColumn('next_profit_time', 'backend.investment.include.__invest_next_profit_time')
                ->rawColumns(['icon', 'schema', 'rio', 'profit', 'capital_back', 'next_profit_time', 'username'])
                ->make(true);
        }

        return view('backend.investment.index');
    }
}
