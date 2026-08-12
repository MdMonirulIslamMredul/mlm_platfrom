<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\PackageOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageOrderController extends Controller
{
    /**
     * Display listing of package orders.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = PackageOrder::with(['user', 'package', 'paymentMethod'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        $pendingCount = PackageOrder::where('status', 'pending')->count();
        $approvedCount = PackageOrder::where('status', 'approved')->count();
        $rejectedCount = PackageOrder::where('status', 'rejected')->count();
        $totalCount = PackageOrder::count();

        return view('admin.package_orders.index', compact(
            'orders',
            'status',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Approve package order and activate investment plan for user.
     */
    public function approve(Request $request, PackageOrder $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending package orders can be approved.');
        }

        DB::transaction(function () use ($order, $request) {
            $order->status = 'approved';
            $order->admin_note = $request->input('admin_note', 'Package order approved by admin');
            $order->save();

            $user = $order->user;
            $package = $order->package;

            // Create Investment record
            Investment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'invested_amount' => $order->package_price,
                'daily_return' => $package->daily_return,
                'status' => 'active',
                'expires_at' => now()->addDays((int) $package->cycle_days),
            ]);

            // Create Transaction record for package purchase
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'package_buy',
                'amount' => $order->package_price,
                'status' => 'completed',
                'description' => 'Package purchase approved via ' . $order->payment_method_name . ': ' . $order->package_name,
            ]);

            // Process referral bonus if user has referrer
            if ($user->referred_by) {
                $referrer = User::find($user->referred_by);
                if ($referrer) {
                    $bonus = $order->package_price * 0.10;

                    $referrer->increment('balance', $bonus);
                    $referrer->increment('total_refer_bonus', $bonus);

                    // Create detailed referral bonus record
                    \App\Models\ReferralBonus::create([
                        'user_id' => $referrer->id,
                        'from_user_id' => $user->id,
                        'package_id' => $package->id,
                        'package_name' => $order->package_name,
                        'package_price' => $order->package_price,
                        'bonus_percentage' => 10.00,
                        'bonus_amount' => $bonus,
                    ]);

                    Transaction::create([
                        'user_id' => $referrer->id,
                        'type' => 'referral_bonus',
                        'amount' => $bonus,
                        'status' => 'completed',
                        'description' => '10% Referral bonus from package order (' . $order->package_name . ') by ' . ($user->name ?? 'User #' . $user->id),
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Package order approved and investment plan activated for user!');
    }

    /**
     * Reject package order.
     */
    public function reject(Request $request, PackageOrder $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending package orders can be rejected.');
        }

        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $order->status = 'rejected';
        $order->admin_note = $request->input('admin_note', 'Package order declined by admin.');
        $order->save();

        return redirect()->back()->with('success', 'Package order declined.');
    }
}
