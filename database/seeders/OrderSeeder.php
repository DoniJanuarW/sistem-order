<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'table_id'     => 4,
            'order_code'   => 'TRX' . now()->format('Ymd') . '001',
            'customer_id'  => null,
            'status'       => 'pending',
            'total_amount' => 60000,
        ]);

        Order::create([
            'table_id'     => 2,
            'order_code'   => 'TRX' . now()->format('Ymd') . '002',
            'customer_id'  => 1,
            'status'       => 'completed',
            'total_amount' => 120000,
        ]);
    }
}
