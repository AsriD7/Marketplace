<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Users
        $admin = User::create(['name'=>'Admin','email'=>'admin@test.local','password'=>Hash::make('password'),'role'=>'admin']);
        $seller = User::create(['name'=>'Penjual','email'=>'penjual@test.local','password'=>Hash::make('password'),'role'=>'penjual']);
        $customer = User::create(['name'=>'Pelanggan','email'=>'pelanggan@test.local','password'=>Hash::make('password'),'role'=>'pelanggan']);

        // Profile minimal
        $customer->profile()->create(['alamat'=>'Jl. Contoh No.1','telepon'=>'081234567890']);

        // Category
        $cat1 = Category::create(['nama'=>'Jajanan Tradisional','slug'=>'jajanan-tradisional']);
        $cat2 = Category::create(['nama'=>'Olahan Laut','slug'=>'olahan-laut']);

        // Store for seller
        $store = Store::create([
            'user_id' => $seller->id,
            'nama_toko' => 'Dapur Mandar',
            'slug' => 'dapur-mandar',
            'deskripsi' => 'Toko makanan khas Mandar',
            'alamat_toko' => 'Pasir Putih',
            'jam_operasional' => '08:00-20:00'
        ]);

        // Products
        Product::create([
            'store_id' => $store->id,
            'category_id' => $cat1->id,
            'nama' => 'Jepa Original',
            'slug' => 'jepa-original',
            'deskripsi' => 'Jepa tradisional enak dari Mandar',
            'harga' => 12000,
            'stok' => 50,
            'is_active' => true,
        ]);
        Product::create([
            'store_id' => $store->id,
            'category_id' => $cat2->id,
            'nama' => 'Ikan Bau Peapi',
            'slug' => 'ikan-bau-peapi',
            'deskripsi' => 'Olahan laut, khas asap tradisional',
            'harga' => 45000,
            'stok' => 20,
            'is_active' => true,
        ]);

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
