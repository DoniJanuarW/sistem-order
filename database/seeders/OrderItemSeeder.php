<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        OrderItem::create([
            'order_id' => 3,
            'menu_id'  => 1,
            'qty'      => 2,
            'note'     => 'Pedas sedang',
            'subtotal' => 50000,
        ]);

        OrderItem::create([
            'order_id' => 3,
            'menu_id'  => 2,
            'qty'      => 2,
            'note'     => null,
            'subtotal' => 10000,
        ]);

        OrderItem::create([
            'order_id' => 2,
            'menu_id'  => 1,
            'qty'      => 3,
            'note'     => 'Tanpa bawang',
            'subtotal' => 75000,
        ]);

        OrderItem::create([
            'order_id' => 2,
            'menu_id'  => 3,
            'qty'      => 1,
            'note'     => null,
            'subtotal' => 45000,
        ]);
    }
}
