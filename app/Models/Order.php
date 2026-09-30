<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'voucher_id',        
        'voucher_code',      
        'discount_amount',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class); 
    }

    public function voucher()
    {
         return $this->belongsTo(Voucher::class);
    }   
}