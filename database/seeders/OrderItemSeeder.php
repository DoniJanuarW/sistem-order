<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Menu;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $menus = Menu::all();

        if ($orders->isEmpty() || $menus->isEmpty()) {
            $this->command->warn("Data Order atau Menu kosong. Seeding OrderItem dibatalkan.");
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $randomMenu = $menus->random();
            $qty = rand(1, 3);
            
            OrderItem::create([
                'order_id' => $orders->random()->id,
                'menu_id'  => $randomMenu->id,
                'qty'      => $qty,
                'note'     => $i % 2 == 0 ? 'Pedas sedang' : null,
                'subtotal' => $randomMenu->price * $qty, 
            ]);
        }
    }
}