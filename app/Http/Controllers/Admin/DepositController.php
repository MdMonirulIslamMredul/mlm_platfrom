<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    /**
     * Display listing of user deposit requests.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Deposit::with(['user', 'paymentMethod'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $deposits = $query->paginate(20);

        $pendingCount = Deposit::where('status', 'pending')->count();
        $approvedCount = Deposit::where('status', 'approved')->count();
        $rejectedCount = Deposit::where('status', 'rejected')->count();
        $totalCount = Deposit::count();

        return view('admin.deposits.index', compact(
            'deposits',
            'status',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Approve user deposit request.
     */
    public function approve(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending deposit requests can be approved.');
        }

        DB::transaction(function () use ($deposit, $request) {
            $deposit->status = 'approved';
            $deposit->admin_note = $request->input('admin_note', 'Deposit approved by admin');
            $deposit->save();

            // Credit user balance
            $user = $deposit->user;
            $user->increment('balance', $deposit->amount);

            // Record transaction log
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $deposit->amount,
                'status' => 'completed',
                'description' => 'Deposit approved via ' . $deposit->payment_method_name . ' (TxID: ' . $deposit->transaction_id . ')',
            ]);
        });

        return redirect()->back()->with('success', 'Deposit of $' . number_format($deposit->amount, 2) . ' approved successfully!');
    }

    /**
     * Reject user deposit request.
     */
    public function reject(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending deposit requests can be rejected.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $deposit->status = 'rejected';
        $deposit->admin_note = $request->input('admin_note', 'Deposit request declined by admin.');
        $deposit->save();

        return redirect()->back()->with('success', 'Deposit request declined.');
    }
}
