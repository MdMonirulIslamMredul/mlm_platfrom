<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Store a new withdrawal request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'account_number' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
        ], [
            'amount.required' => 'Please enter the withdrawal amount.',
            'amount.min' => 'Minimum withdrawal amount is ৳10.00.',
            'payment_method_id.required' => 'Please select a withdrawal payment method.',
            'account_number.required' => 'Please enter your receiving account/phone number.',
        ]);

        $amount = (float) $request->amount;

        if ($user->balance < $amount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Insufficient balance for this withdrawal request. Available balance: ৳' . number_format($user->balance, 2));
        }

        $paymentMethod = PaymentMethod::where('id', $request->payment_method_id)
            ->where('is_active', true)
            ->first();

        if (!$paymentMethod) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected payment method is currently disabled or unavailable.');
        }

        DB::transaction(function () use ($user, $paymentMethod, $amount, $request) {
            // Deduct user balance immediately to lock funds
            $user->decrement('balance', $amount);

            // Create withdrawal request log
            Withdrawal::create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
                'amount' => $amount,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'status' => 'pending',
            ]);

            // Create transaction log with status pending
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $amount,
                'status' => 'pending',
                'description' => 'Withdrawal request via ' . $paymentMethod->name . ' (Acc: ' . $request->account_number . ')',
            ]);
        });

        return redirect()->back()
            ->with('success', 'Withdrawal request of ৳' . number_format($amount, 2) . ' submitted successfully! Admin will review your request shortly.');
    }
}
