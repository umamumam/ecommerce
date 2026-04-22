<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'total_price',
        'shipping_price',
        'grand_total',
        'status',
        'shipping_courier',
        'shipping_service',
        'shipping_waybill',
        'shipping_area_id',
        'shipping_address',
        'shipping_postal_code',
        'shipping_name',
        'shipping_phone',
        'biteship_order_id',
        'payment_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
