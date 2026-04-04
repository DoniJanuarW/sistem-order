<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
