<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KYCStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Gateway;
use App\Models\Invest;
use App\Models\ReferralRelationship;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //admin dashboard
    public function dashboard()
    {

        $dayName = ['Sun' => 0, 'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0];


        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        $totalDeposit = $transaction->totalDeposit();

        $totalSend = Transaction::where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })->sum('amount');

        $activeUser = $user->where('status', 1)->count();

        $totalStaff = $admin->count();

        $latestUser = $user->latest()->take(5)->get();

        $latestInvest = Invest::with('schema')->take(5)->get();

        $totalGateway = Gateway::where('status', true)->count();


        $withdrawCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->where('status', 'pending');
        })->count();

        $kycCount = $user->where('kyc', KYCStatus::Pending)->count();


        $depositCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::ManualDeposit)
                ->where('status', 'pending');
        })->count();


        $totalInvestment = $transaction->totalInvestment();

        $totalWithdraw = Transaction::where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::Withdraw);
        })->sum('amount');

        $totalReferral = ReferralRelationship::count();


        $today = Carbon::now()->subDays(7);

        $last7daysDeposit = $totalDeposit->where('created_at', '>=', $today)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $last7daysDeposit = array_merge($dayName, $last7daysDeposit);

        $last7daysInvest = $totalInvestment->where('created_at', '>=', $today)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $dataRange = 'Date: ' . $today->format('d-m-y') . ' to ' . Carbon::now()->format('d-m-y');


        $last7daysInvest = array_merge($dayName, $last7daysInvest);

        $data = [
            'withdraw_count' => $withdrawCount,
            'kyc_count' => $kycCount,
            'deposit_count' => $depositCount,

            'register_user' => $user->count(),
            'active_user' => $activeUser,
            'latest_user' => $latestUser,
            'latest_invest' => $latestInvest,

            'total_staff' => $totalStaff,

            'total_deposit' => $transaction->totalDeposit()->sum('amount'),
            'total_send' => $totalSend,
            'total_investment' => $transaction->totalInvestment()->sum('amount'),
            'total_withdraw' => $totalWithdraw,
            'total_referral' => $totalReferral,

            'last7days_deposit' => $last7daysDeposit,
            'last7days_invest' => $last7daysInvest,

            'deposit_bonus' => $transaction->totalDepositBonus(),
            'investment_bonus' => $transaction->totalInvestBonus(),
            'total_gateway' => $totalGateway,
            'total_ticket' => Ticket::count(),

            'date_range' => $dataRange,

        ];

        return view('backend.dashboard', compact('data'));
    }
}
