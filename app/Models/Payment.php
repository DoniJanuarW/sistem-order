<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];    

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function orderItems()
    {
       return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
