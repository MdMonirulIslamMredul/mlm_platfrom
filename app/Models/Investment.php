<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'invested_amount',
        'daily_return',
        'days_received',
        'total_earned',
        'last_payout_at',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_payout_at' => 'datetime',
        'days_received' => 'integer',
        'total_earned' => 'decimal:2',
    ];

    /**
     * Accessor for calculating days remaining on active plan.
     */
    public function getDaysLeftAttribute()
    {
        if ($this->status !== 'active' || !$this->expires_at) {
            return 0;
        }

        $seconds = now()->diffInSeconds($this->expires_at, false);
        return max(0, (int) ceil($seconds / 86400));
    }

    /**
     * Accessor for calculating percentage completed of cycle_days.
     */
    public function getProgressPercentAttribute()
    {
        $totalDays = $this->package->cycle_days ?? 30;
        if ($totalDays <= 0) {
            return 100;
        }
        return min(100, (int) round(($this->days_received / $totalDays) * 100));
    }

    /**
     * Get the user that owns the investment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the investment.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
