<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Menu; // <-- 1. Wajib ditambahkan

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $menus = Menu::all(); // <-- 2. Wajib didefinisikan

        // Pastikan ada data untuk diacak
        if ($orders->isEmpty() || $menus->isEmpty()) {
            $this->command->warn("Data Order atau Menu kosong. Seeding Payment dibatalkan.");
            return;
        }
        
        for ($i = 0; $i < 5; $i++) {
            $randomMenu = $menus->random();
            $qty = rand(1, 3);
            $totalAmount = $randomMenu->price * $qty;
            $isPaid = $i % 2 == 0;

            Payment::create([
                'order_id'       => $orders->random()->id,
                'method'         => $isPaid ? 'qris' : 'cash',
                'amount'         => $totalAmount,
                'payment_status' => $isPaid ? 'success' : 'pending',
                'paid_at'        => $isPaid ? now() : null,
            ]);
        }
    }
}