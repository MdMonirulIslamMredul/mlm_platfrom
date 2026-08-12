<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Package;
use App\Models\PackageOrder;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Handle purchasing an investment package using wallet balance.
     */
    public function buyPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);

        // Check if the authenticated user has enough balance
        if ($user->balance < $package->price) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance to purchase this package.'
                ], 400);
            }
            return back()->with('error', 'Insufficient balance to purchase this package.');
        }

        DB::transaction(function () use ($user, $package) {
            // Deduct the package price from the authenticated user's balance
            $user->decrement('balance', $package->price);

            // Create a record in the investments table
            Investment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'invested_amount' => $package->price,
                'daily_return' => $package->daily_return,
                'status' => 'active',
                'expires_at' => now()->addDays((int) $package->cycle_days),
            ]);

            // Create a record in the transactions table for the buyer
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'package_buy',
                'amount' => $package->price,
                'status' => 'completed',
                'description' => 'Purchased package: ' . $package->name,
            ]);

            // Check if the buyer has a referred_by ID
            if ($user->referred_by) {
                $referrer = User::find($user->referred_by);

                if ($referrer) {
                    // Calculate a 10% referral bonus from the package price
                    $bonus = $package->price * 0.10;

                    // Add the bonus to the referrer's balance and total_refer_bonus
                    $referrer->increment('balance', $bonus);
                    $referrer->increment('total_refer_bonus', $bonus);

                    // Create a detailed referral bonus record
                    \App\Models\ReferralBonus::create([
                        'user_id' => $referrer->id,
                        'from_user_id' => $user->id,
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'package_price' => $package->price,
                        'bonus_percentage' => 10.00,
                        'bonus_amount' => $bonus,
                    ]);

                    // Create a record in the transactions table for the referrer
                    Transaction::create([
                        'user_id' => $referrer->id,
                        'type' => 'referral_bonus',
                        'amount' => $bonus,
                        'status' => 'completed',
                        'description' => '10% Referral bonus from package purchase (' . $package->name . ') by ' . ($user->name ?? 'User #' . $user->id),
                    ]);
                }
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Package purchased successfully!'
            ]);
        }

        return back()->with('success', 'Package purchased successfully!');
    }

    /**
     * Show package direct payment checkout page.
     */
    public function checkout(Package $package)
    {
        $user = Auth::user();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('frontend.package_checkout', compact('package', 'paymentMethods', 'user'));
    }

    /**
     * Store package order with manual payment receipt.
     */
    public function storeOrder(Request $request, Package $package)
    {
        $user = Auth::user();

        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'payment_method_id.required' => 'Please select a payment method.',
            'user_name.required' => 'Please enter your name.',
            'user_phone.required' => 'Please enter your phone number.',
            'transaction_id.required' => 'Please enter the transaction ID/number.',
            'screenshot.required' => 'Please upload a transaction screenshot receipt.',
            'screenshot.image' => 'The file must be a valid image.',
        ]);

        $paymentMethod = PaymentMethod::where('id', $request->payment_method_id)
            ->where('is_active', true)
            ->firstOrFail();

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = 'pkg_order_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/package_orders'), $filename);
            $screenshotPath = 'uploads/package_orders/' . $filename;
        }

        PackageOrder::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'payment_method_id' => $paymentMethod->id,
            'payment_method_name' => $paymentMethod->name,
            'payment_method_type' => $paymentMethod->type,
            'user_name' => $request->user_name,
            'user_phone' => $request->user_phone,
            'transaction_id' => $request->transaction_id,
            'screenshot' => $screenshotPath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.plans')
            ->with('success', 'Package order submitted successfully! Admin will verify your payment and activate your package shortly.');
    }
}
