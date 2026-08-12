<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of user withdrawal requests.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Withdrawal::with(['user', 'paymentMethod'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $withdrawals = $query->paginate(20);

        $pendingCount = Withdrawal::where('status', 'pending')->count();
        $approvedCount = Withdrawal::where('status', 'approved')->count();
        $rejectedCount = Withdrawal::where('status', 'rejected')->count();
        $totalCount = Withdrawal::count();

        return view('admin.withdrawals.index', compact(
            'withdrawals',
            'status',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Approve user withdrawal request.
     */
    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending withdrawal requests can be approved.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->status = 'approved';
            $withdrawal->admin_note = $request->input('admin_note', 'Withdrawal request approved by admin');
            $withdrawal->save();

            // Update matching pending transaction log to completed
            $transaction = Transaction::where('user_id', $withdrawal->user_id)
                ->where('type', 'withdraw')
                ->where('status', 'pending')
                ->where('amount', $withdrawal->amount)
                ->latest()
                ->first();

            if ($transaction) {
                $transaction->update([
                    'status' => 'completed',
                    'description' => 'Withdrawal approved via ' . $withdrawal->payment_method_name . ' (Acc: ' . $withdrawal->account_number . ')',
                ]);
            }
        });

        return redirect()->back()->with('success', 'Withdrawal request of ৳' . number_format($withdrawal->amount, 2) . ' approved successfully!');
    }

    /**
     * Reject user withdrawal request and refund balance.
     */
    public function reject(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending withdrawal requests can be rejected.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->status = 'rejected';
            $withdrawal->admin_note = $request->input('admin_note', 'Withdrawal request declined by admin.');
            $withdrawal->save();

            // Refund user balance
            $user = $withdrawal->user;
            if ($user) {
                $user->increment('balance', $withdrawal->amount);
            }

            // Update matching pending transaction log to rejected
            $transaction = Transaction::where('user_id', $withdrawal->user_id)
                ->where('type', 'withdraw')
                ->where('status', 'pending')
                ->where('amount', $withdrawal->amount)
                ->latest()
                ->first();

            if ($transaction) {
                $transaction->update([
                    'status' => 'rejected',
                    'description' => 'Withdrawal declined: ' . ($withdrawal->admin_note ?? 'Refunded to balance'),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Withdrawal request declined and ৳' . number_format($withdrawal->amount, 2) . ' refunded back to user balance.');
    }
}
