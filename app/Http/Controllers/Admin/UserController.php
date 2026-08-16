<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|in:user,admin,super_admin',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'] ?? $user->name;
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        if (isset($validated['role'])) {
            $user->role = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::check() && Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Display detailed activity overview for a specific user.
     */
    public function activity(User $user)
    {
        $teamMembers = $user->team()->latest()->paginate(15, ['*'], 'team_page');
        $totalTeamCount = $user->team()->count();
        $teamUserIds = $user->team()->pluck('id');

        $investments = $user->investments()->with('package')->latest()->paginate(15, ['*'], 'invest_page');
        $transactions = $user->transactions()->latest()->paginate(15, ['*'], 'trans_page');
        $withdrawals = $user->withdrawals()->with('paymentMethod')->latest()->paginate(15, ['*'], 'withdraw_page');

        $totalTeamInvest = \App\Models\Investment::whereIn('user_id', $teamUserIds)->sum('invested_amount');
        $totalTeamWithdrawals = \App\Models\Transaction::whereIn('user_id', $teamUserIds)
            ->where('type', 'withdraw')
            ->where('status', 'completed')
            ->sum('amount');

        $totalUserWithdrawals = $user->withdrawals()->where('status', 'approved')->sum('amount');

        $activePlans = $user->investments()->where('status', 'active')->count();
        $referrer = $user->referrer;

        return view('admin.users.activity', compact(
            'user',
            'teamMembers',
            'totalTeamCount',
            'investments',
            'transactions',
            'withdrawals',
            'totalTeamInvest',
            'totalTeamWithdrawals',
            'totalUserWithdrawals',
            'activePlans',
            'referrer'
        ));
    }
}
