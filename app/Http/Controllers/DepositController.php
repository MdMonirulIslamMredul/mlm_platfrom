<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Show deposit money page for user.
     */
    public function index()
    {
        $user = Auth::user();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $deposits = Deposit::where('user_id', $user->id)
            ->with('paymentMethod')
            ->latest()
            ->paginate(10);
        $balance = $user->balance;

        return view('frontend.deposit', compact('user', 'paymentMethods', 'deposits', 'balance'));
    }

    /**
     * Store new deposit request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'amount.required' => 'Please enter deposit amount.',
            'amount.min' => 'Minimum deposit amount is 1.',
            'payment_method_id.required' => 'Please select a payment method.',
            'user_name.required' => 'Please enter your name.',
            'user_phone.required' => 'Please enter your phone number.',
            'transaction_id.required' => 'Please enter the transaction number/ID.',
            'screenshot.required' => 'Please upload a transaction screenshot.',
            'screenshot.image' => 'The uploaded file must be an image.',
        ]);

        $paymentMethod = PaymentMethod::where('id', $request->payment_method_id)
            ->where('is_active', true)
            ->firstOrFail();

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = 'dep_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/deposits'), $filename);
            $screenshotPath = 'uploads/deposits/' . $filename;
        }

        Deposit::create([
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_method_name' => $paymentMethod->name,
            'payment_method_type' => $paymentMethod->type,
            'amount' => $request->amount,
            'user_name' => $request->user_name,
            'user_phone' => $request->user_phone,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $screenshotPath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.deposit')
            ->with('success', 'Deposit request submitted successfully! Admin will review and approve your deposit shortly.');
    }
}
