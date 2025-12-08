<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductRating;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // tampilkan history order untuk pelanggan
    public function index()
    {
        $user = auth()->user();
        $orders = Order::where('user_id', $user->id)
                       ->with('store')
                       ->orderBy('created_at','desc')
                       ->paginate(12);

        return view('orders.index', compact('orders'));
    }

    // detail order (dengan list item + form rating per item jika eligible)
    public function show(Order $order)
    {
        $user = auth()->user();

        // pastikan owner
        if ($order->user_id !== $user->id) abort(403);

        $order->load('items.product.category', 'store', 'payment');

        // for convenience, get products that are already rated in this order by this user
        $ratedProductIds = ProductRating::where('order_id', $order->id)
                            ->where('user_id', $user->id)
                            ->pluck('product_id')
                            ->toArray();

        return view('orders.show', compact('order', 'ratedProductIds'));
    }

    /**
     * Store rating(s) for items in an order.
     * Expect input like:
     * ratings => [
     *   product_id => ['rating' => 5, 'review' => '...'],
     *   ...
     * ]
     */
    public function storeRating(Request $request, Order $order)
    {
        $user = auth()->user();

        // owner check
        if ($order->user_id !== $user->id) abort(403);

        // only allow rating if order status delivered/completed
        if (! in_array($order->status, ['delivered','completed'])) {
            return back()->with('error', 'Anda hanya dapat memberi ulasan setelah pesanan diterima.');
        }

        $data = $request->validate([
            'ratings' => 'required|array',
            'ratings.*.product_id' => 'required|exists:products,id',
            'ratings.*.rating' => 'required|integer|min:1|max:5',
            'ratings.*.komentar' => 'nullable|string|max:2000',
        ]);

        // We'll insert ratings within transaction and skip duplicates
        DB::beginTransaction();
        try {
            foreach ($data['ratings'] as $item) {
                $productId = $item['product_id'];
                $ratingValue = (int)$item['rating'];
                $reviewText = $item['komentar'] ?? null;

                // ensure product is part of this order
                $existsInOrder = $order->items()->where('product_id', $productId)->exists();
                if (! $existsInOrder) {
                    // skip or abort; safer to skip and continue
                    continue;
                }

                // ensure not rated already for this order-item by this user
                $already = ProductRating::where('order_id', $order->id)
                            ->where('product_id', $productId)
                            ->where('user_id', $user->id)
                            ->exists();
                if ($already) {
                    // skip duplicates
                    continue;
                }

                ProductRating::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'order_id' => $order->id,
                    'rating' => $ratingValue,
                    'komentar' => $reviewText,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Terima kasih! Ulasan Anda telah disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan ulasan: ' . $e->getMessage());
        }
    }

}
