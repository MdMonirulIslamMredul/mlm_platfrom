<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'package_name',
        'package_price',
        'payment_method_id',
        'payment_method_name',
        'payment_method_type',
        'user_name',
        'user_phone',
        'transaction_id',
        'screenshot',
        'status',
        'admin_note',
    ];

    /**
     * Get the user who made the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ordered package.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the payment method used.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
