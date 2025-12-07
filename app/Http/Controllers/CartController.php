<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
    // tampilkan isi keranjang
    public function index()
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product')->first();

        // jika tidak ada cart => keranjang kosong
        $items = $cart ? $cart->items : collect();

        $total = $items->sum(function($i) {
            return $i->qty * $i->harga_saat_ini;
        });

        return view('cart.index', compact('items','total'));
    }

    // tambah produk ke keranjang
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'nullable|integer|min:1',
        ]);

        $qty = $request->qty ?? 1;
        $product = Product::findOrFail($request->product_id);

        // cek stok
        if ($product->stok < $qty) {
            return back()->with('error', 'Stok tidak cukup untuk produk ini.');
        }

        $user = auth()->user();

        DB::transaction(function () use ($user, $product, $qty) {
            $cart = $user->cart()->firstOrCreate([]);

            // update atau buat cart item
            $item = $cart->items()->where('product_id', $product->id)->first();
            if ($item) {
                $newQty = $item->qty + $qty;

                // validasi stok lagi
                if ($product->stok < $newQty) {
                    throw new \Exception('Stok tidak cukup saat menambah jumlah di keranjang.');
                }

                $item->update([
                    'qty' => $newQty,
                    'harga_saat_ini' => $product->harga,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'harga_saat_ini' => $product->harga,
                ]);
            }
        });

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    // update qty item
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        // pastikan item milik user
        if ($item->cart->user_id !== $user->id) {
            abort(403);
        }

        $product = $item->product;
        $newQty = (int)$request->qty;

        if ($product->stok < $newQty) {
            return back()->with('error', 'Stok tidak cukup untuk jumlah yang diminta.');
        }

        $item->update(['qty' => $newQty]);

        return back()->with('success', 'Jumlah keranjang diperbarui');
    }

    // hapus item
    public function destroy(CartItem $item)
    {
        $user = auth()->user();
        if ($item->cart->user_id !== $user->id) abort(403);

        $item->delete();
        return back()->with('success', 'Item dihapus dari keranjang');
    }

    // clear cart (opsional)
    public function clear()
    {
        $user = auth()->user();
        $cart = $user->cart;
        if ($cart) {
            $cart->items()->delete();
        }
        return back()->with('success', 'Keranjang dikosongkan');
    }

}
