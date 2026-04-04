<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        Payment::create([
            'order_id'       => 3,
            'method'         => 'cash',
            'amount'         => 60000,
            'payment_status' => 'pending',
            'paid_at'        => null,
        ]);

        Payment::create([
            'order_id'       => 2,
            'method'         => 'qris',
            'amount'         => 120000,
            'payment_status' => 'success',
            'paid_at'        => now(),
        ]);
    }
}
