<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['admin', 'super_admin'], true)) {
            return redirect()->route('admin.dashboard');
        }

        // Process any due daily profit returns for this user upon dashboard load
        \App\Services\DailyReturnService::processUserReturns($user);
        $user->refresh();

        $teamUserIds = $user->team()->pluck('id');
        $totalTeam = $user->team()->count();
        $balance = $user->balance;
        $totalReferBonus = $user->total_refer_bonus;
        $activePlans = $user->investments()->where('status', 'active')->count();

        $totalWithdrawals = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $pendingWithdraw = $user->withdrawals()->where('status', 'pending')->sum('amount');
        $pendingDeposit = $user->deposits()->where('status', 'pending')->sum('amount');
        $totalRecharge = $user->deposits()->where('status', 'approved')->sum('amount');

        $teamTotalWithdrawals = \App\Models\Transaction::whereIn('user_id', $teamUserIds)
            ->where('type', 'withdraw')
            ->where('status', 'completed')
            ->sum('amount');
        $teamTotalInvest = \App\Models\Investment::whereIn('user_id', $teamUserIds)->sum('invested_amount');

        $recentInvestments = $user->investments()->with('package')->latest()->take(5)->get();
        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        $recentPackageOrders = $user->packageOrders()->latest()->take(5)->get();
        $packages = \App\Models\Package::latest()->get();
        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();

        return view('frontend.dashboard', compact(
            'user',
            'totalTeam',
            'balance',
            'totalReferBonus',
            'activePlans',
            'totalWithdrawals',
            'pendingWithdraw',
            'pendingDeposit',
            'totalRecharge',
            'teamTotalWithdrawals',
            'teamTotalInvest',
            'recentInvestments',
            'recentTransactions',
            'recentPackageOrders',
            'packages',
            'paymentMethods'
        ));
    }

    /**
     * Display user's total investment plans page.
     */
    public function plans()
    {
        $user = Auth::user();
        $investments = $user->investments()->with('package')->latest()->paginate(15);

        return view('frontend.plans', compact('user', 'investments'));
    }

    /**
     * Display team total withdrawals page.
     */
    public function teamWithdrawals()
    {
        $user = Auth::user();
        $teamUserIds = $user->team()->pluck('id');
        $teamWithdrawals = \App\Models\Transaction::whereIn('user_id', $teamUserIds)
            ->where('type', 'withdraw')
            ->with('user')
            ->latest()
            ->paginate(15);
        $totalTeamWithdrawals = \App\Models\Transaction::whereIn('user_id', $teamUserIds)
            ->where('type', 'withdraw')
            ->where('status', 'completed')
            ->sum('amount');

        return view('frontend.team_withdrawals', compact('user', 'teamWithdrawals', 'totalTeamWithdrawals'));
    }

    /**
     * Display total refer bonus history page.
     */
    public function referralBonus()
    {
        $user = Auth::user();
        $bonuses = \App\Models\ReferralBonus::where('user_id', $user->id)
            ->with(['fromUser', 'package'])
            ->latest()
            ->paginate(15);

        $fallbackBonuses = null;
        if ($bonuses->isEmpty()) {
            $fallbackBonuses = $user->transactions()
                ->where('type', 'referral_bonus')
                ->latest()
                ->paginate(15);
        }

        return view('frontend.referral_bonus', compact('user', 'bonuses', 'fallbackBonuses'));
    }

    /**
     * Display total team investments page.
     */
    public function teamInvest()
    {
        $user = Auth::user();
        $teamUserIds = $user->team()->pluck('id');
        $teamInvestments = \App\Models\Investment::whereIn('user_id', $teamUserIds)
            ->with(['user', 'package'])
            ->latest()
            ->paginate(15);
        $totalTeamInvest = \App\Models\Investment::whereIn('user_id', $teamUserIds)->sum('invested_amount');

        return view('frontend.team_invest', compact('user', 'teamInvestments', 'totalTeamInvest'));
    }

    /**
     * Display direct team members page.
     */
    public function team()
    {
        $user = Auth::user();
        $teamMembers = $user->team()->latest()->paginate(15);
        $totalTeam = $user->team()->count();

        return view('frontend.team', compact('user', 'teamMembers', 'totalTeam'));
    }

    /**
     * Display available investment packages page.
     */
    public function packages()
    {
        $user = Auth::user();
        $balance = $user->balance;
        $packages = \App\Models\Package::latest()->get();
        $packageOrders = $user->packageOrders()->latest()->paginate(10);

        return view('frontend.packages', compact('user', 'balance', 'packages', 'packageOrders'));
    }

    /**
     * Display account history page with transactions & investments logs.
     */
    public function history()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->latest()->paginate(15);
        $investments = $user->investments()->with('package')->latest()->take(10)->get();

        return view('frontend.history', compact('user', 'transactions', 'investments'));
    }

    /**
     * Display user profile and password change page.
     */
    public function profile()
    {
        $user = Auth::user();

        return view('frontend.profile', compact('user'));
    }

    /**
     * Update user profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Profile details updated successfully!');
    }

    /**
     * Change user account password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Display full user balance history page with type filtering.
     */
    public function balanceHistory(Request $request)
    {
        $user = Auth::user();
        $filterType = $request->query('type', 'all');

        $query = $user->transactions();

        if (in_array($filterType, ['deposit', 'withdraw', 'referral_bonus', 'daily_return', 'package_buy'], true)) {
            $query->where('type', $filterType);
        }

        $transactions = $query->latest()->paginate(15);

        // Calculate summary numbers
        $totalDeposits = $user->deposits()->where('status', 'approved')->sum('amount');
        $totalWithdrawals = $user->transactions()->where('type', 'withdraw')->where('status', 'completed')->sum('amount');
        $totalReferralBonuses = $user->total_refer_bonus;
        $totalDailyReturns = $user->transactions()->where('type', 'daily_return')->where('status', 'completed')->sum('amount');
        if ($totalDailyReturns == 0) {
            $totalDailyReturns = (float) $user->investments()->sum('total_earned');
        }
        $totalPackageBuy = $user->investments()->sum('invested_amount');

        return view('frontend.balance_history', compact(
            'user',
            'transactions',
            'filterType',
            'totalDeposits',
            'totalWithdrawals',
            'totalReferralBonuses',
            'totalDailyReturns',
            'totalPackageBuy'
        ));
    }
}
