<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DailyReturnService
{
    /**
     * Process daily profit return payouts for a specific user's active investments.
     * Supports multi-day catch-up if a user didn't log in for several days.
     */
    public static function processUserReturns(User $user): int
    {
        $activeInvestments = Investment::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('package')
            ->get();

        $processedCount = 0;

        foreach ($activeInvestments as $investment) {
            if (!$investment->package) {
                continue;
            }

            $cycleDays = $investment->package->cycle_days ?? 30;

            // Check if investment plan has already reached max cycle days
            if ($investment->days_received >= $cycleDays) {
                $investment->update(['status' => 'completed']);
                continue;
            }

            // Skip if payout was already credited today
            if ($investment->last_payout_at && $investment->last_payout_at->isToday()) {
                continue;
            }

            // Determine start reference date for calculating due days
            $startRefDate = $investment->last_payout_at
                ? $investment->last_payout_at->startOfDay()
                : $investment->created_at->startOfDay();

            $today = now()->startOfDay();

            // Calculate how many full days have elapsed since start reference date
            $daysElapsed = (int) $startRefDate->diffInDays($today);

            if ($daysElapsed <= 0) {
                // Same day as purchase or last payout -> skip
                continue;
            }

            // Cap the days to pay by remaining cycle days
            $remainingCycleDays = max(0, $cycleDays - $investment->days_received);
            $daysToPay = min($daysElapsed, $remainingCycleDays);

            if ($daysToPay <= 0) {
                $investment->update(['status' => 'completed']);
                continue;
            }

            // Process catch-up payouts for all due days
            DB::transaction(function () use ($user, $investment, $daysToPay, $cycleDays, &$processedCount) {
                $dailyReturn = $investment->daily_return;
                $totalReturnAmount = $dailyReturn * $daysToPay;

                // Credit user's wallet balance for all catch-up days
                $user->increment('balance', $totalReturnAmount);

                $newDaysReceived = $investment->days_received + $daysToPay;
                $newTotalEarned = $investment->total_earned + $totalReturnAmount;
                $isCompleted = ($newDaysReceived >= $cycleDays || (bool) ($investment->expires_at && now()->gte($investment->expires_at)));

                $investment->update([
                    'days_received' => $newDaysReceived,
                    'total_earned' => $newTotalEarned,
                    'last_payout_at' => now(),
                    'status' => $isCompleted ? 'completed' : 'active',
                ]);

                // Record transaction log(s)
                if ($daysToPay === 1) {
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'daily_return',
                        'amount' => $dailyReturn,
                        'status' => 'completed',
                        'description' => 'Daily return income for ' . $investment->package->name . ' (Day ' . $newDaysReceived . ' of ' . $cycleDays . ')',
                    ]);
                } else {
                    Transaction::create([
                        'user_id' => $user->id,
                        'type' => 'daily_return',
                        'amount' => $totalReturnAmount,
                        'status' => 'completed',
                        'description' => 'Daily return income for ' . $investment->package->name . ' (' . $daysToPay . ' days catch-up: Day ' . ($newDaysReceived - $daysToPay + 1) . ' to ' . $newDaysReceived . ' of ' . $cycleDays . ')',
                    ]);
                }

                $processedCount += $daysToPay;
            });
        }

        return $processedCount;
    }
}
