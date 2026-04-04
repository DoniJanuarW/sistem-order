<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('name', 'Makanan')->first()->id;
        $minuman = Category::where('name', 'Minuman')->first()->id;

        Menu::insert([
            [
                'category_id' => $makanan,
                'name' => 'Nasi Goreng Spesial',
                'price' => 35000,
                'description' => 'Nasi goreng dengan telur dan ayam',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $makanan,
                'name' => 'Mie Goreng',
                'price' => 30000,
                'description' => 'Mie goreng khas restoran',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $minuman,
                'name' => 'Es Teh Manis',
                'price' => 8000,
                'description' => 'Teh manis dingin',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $minuman,
                'name' => 'Jus Alpukat',
                'price' => 20000,
                'description' => 'Jus alpukat segar',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
