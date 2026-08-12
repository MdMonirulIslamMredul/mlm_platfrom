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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cycle_days' => 'integer',
        'price' => 'decimal:2',
        'daily_return' => 'decimal:2',
    ];

    /**
     * Get investments for this package.
     */
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
