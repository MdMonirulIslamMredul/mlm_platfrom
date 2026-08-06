<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'cycle_days',
        'daily_return',
    ];

    /**
     * Get investments for this package.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
