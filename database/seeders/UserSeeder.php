<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin Master',
                'full_name' => 'Admin Master',
                'email' => 'admin@grandsanthi.com',
                'phone' => '08912343111',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir',
                'full_name' => 'Cashier',
                'email' => 'kasir@grandsanthi.com',
                'phone' => '08912343121',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Customer Demo',
                'full_name' => 'Customer',
                'email' => 'customer@demo.com',
                'phone' => '08912343141',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
