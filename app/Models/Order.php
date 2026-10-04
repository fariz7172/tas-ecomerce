<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'address_line',
        'province',
        'city',
        'district',
        'courier_code',
        'courier_service',
        'shipping_cost',
        'subtotal',
        'grand_total',
        'status',
        'payment_method',
        'payment_channel',
        'payment_payload',
        'tracking_number',
        'paid_at',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
