<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Penjual',
            'email' => 'penjual@example.com',
            'password' => bcrypt('password'),
            'role' => 'penjual',
        ]);
        User::create([
            'name' => 'Pelanggan',
            'email' => 'pelanggan@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);
    }
}
