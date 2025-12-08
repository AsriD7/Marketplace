<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;

class RatingController extends Controller
{
    //
    /**
     * Store rating & review
     * Request:
     * - order_id
     * - product_id
     * - rating (1..5)
     * - review (nullable)
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();
        $order = Order::findOrFail($request->order_id);
        $product = Product::findOrFail($request->product_id);

        // pastikan order milik user
        if ($order->user_id !== $user->id) {
            abort(403, 'Order bukan milik Anda.');
        }

        // hanya boleh rate jika order sudah delivereD/completed (sesuaikan status projectmu)
        if (! in_array($order->status, ['delivered','completed'])) {
            return back()->with('error', 'Anda hanya dapat memberi rating setelah pesanan diterima.');
        }

        // pastikan product ada di order items
        $existsInOrder = $order->items()->where('product_id', $product->id)->exists();
        if (! $existsInOrder) {
            abort(403, 'Produk ini tidak ada di pesanan tersebut.');
        }

        // pastikan belum pernah memberi rating untuk kombinasi order-item
        $already = ProductRating::where('order_id', $order->id)
                    ->where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->exists();

        if ($already) {
            return back()->with('error','Anda sudah memberi rating untuk produk ini pada pesanan ini.');
        }

        // simpan rating
        ProductRating::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => (int)$request->rating,
            'komentar' => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih! Rating Anda telah tersimpan.');
    }

}
