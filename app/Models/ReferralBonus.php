<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'package_id',
        'package_name',
        'package_price',
        'bonus_percentage',
        'bonus_amount',
    ];

    /**
     * Get the referrer user who received the bonus.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the referred team member user who purchased the package.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the package purchased.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
