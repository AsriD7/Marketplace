<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    //
    // tampilkan halaman checkout (ringkasan)
    public function show()
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product.store','items.product.category')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('catalog.index')->with('error', 'Keranjang anda kosong.');
        }

        // group items per store
        $groups = $cart->items->groupBy(fn($i) => $i->product->store->id ?? 0);

        // alamat default dari profile (jika ada)
        $alamat_default = optional($user->profile)->alamat ?? '';

        return view('checkout.show', compact('cart','groups','alamat_default'));
    }

    // proses checkout: buat order (satu per store), lock stock, insert order_items, kosongkan cart
    public function store(Request $request)
    {
        $request->validate([
            'alamat_kirim' => 'required|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            // nanti tambah metode pengiriman/ongkir kalau perlu
        ]);

        $user = auth()->user();
        $cart = $user->cart()->with('items.product.store')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // group items per store
        $groups = $cart->items->groupBy(fn($i) => $i->product->store->id ?? 0);

        $createdOrders = [];

        DB::beginTransaction();
        try {
            foreach ($groups as $storeId => $items) {
                // hitung subtotal untuk store ini
                $subtotal = 0;
                foreach ($items as $it) {
                    $subtotal += $it->qty * $it->harga_saat_ini;
                }

                // placeholder ongkir (0) - bisa diganti per store
                $ongkir = 0;
                $total = $subtotal + $ongkir;

                // buat order header
                $order = Order::create([
                    'order_number' => 'ORD-' . time() . '-' . Str::upper(Str::random(4)),
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'alamat_kirim' => $request->alamat_kirim,
                    'catatan' => $request->catatan ?? null,
                    'subtotal' => $subtotal,
                    'ongkir' => $ongkir,
                    'total' => $total,
                    'status' => 'created',
                    'payment_status' => 'pending',
                ]);

                // untuk setiap item: cek stok dengan lockForUpdate, decrement stok, buat order_item
                foreach ($items as $it) {
                    $product = Product::lockForUpdate()->find($it->product_id);
                    if (! $product) {
                        throw new \Exception("Produk tidak ditemukan: ID {$it->product_id}");
                    }

                    if ($product->stok < $it->qty) {
                        throw new \Exception("Stok tidak cukup untuk produk: {$product->nama}");
                    }

                    // reduce stok (business rule: reduce now)
                    $product->decrement('stok', $it->qty);

                    // create order item snapshot
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->nama,
                        'qty' => $it->qty,
                        'harga' => $it->harga_saat_ini,
                    ]);
                }

                $createdOrders[] = $order;
            }

            // kosongkan cart seluruhnya setelah semua order berhasil
            $cart->items()->delete();

            DB::commit();

            // jika hanya satu order dibuat, redirect ke halaman order itu; jika banyak, bisa redirect ke daftar orders
            if (count($createdOrders) === 1) {
                return redirect()->route('orders.show', $createdOrders[0]->id)
                                 ->with('success', 'Order dibuat. Silakan unggah bukti pembayaran.');
            } else {
                // buat halaman ringkasan beberapa order (atau ke daftar order)
                return redirect()->route('orders.index') // kalau ada
                                 ->with('success', 'Beberapa order berhasil dibuat. Silakan cek daftar pesanan Anda.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

}
