<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    //
    /**
     * Tampilkan daftar order masuk untuk toko seller (payment sudah valid)
     */
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        if (! $store) {
            return redirect()->route('penjual.store.edit')->with('error', 'Buat toko dulu untuk melihat pesanan.');
        }

        // ambil order untuk store ini, urut terbaru
        $orders = Order::where('store_id', $store->id)
                        ->with('user','items.product')
                        ->orderBy('created_at','desc')
                        ->paginate(20);

        return view('penjual.orders.index', compact('orders'));
    }

    /**
     * Detail order untuk seller (lihat items, alamat, payment)
     */
    public function show(Order $order)
    {
        $user = auth()->user();
        $store = $user->store;

        // safety: pastikan order milik seller (store)
        if (! $store || $order->store_id !== $store->id) {
            abort(403, 'Unauthorized.');
        }

        $order->load('items.product','user','payment');

        return view('penjual.orders.show', compact('order'));
    }

    /**
     * Update status order oleh seller dan upload resi (opsional)
     * Allowed status transitions for seller:
     *  - payment_validated -> processing (sedang diproses)
     *  - processing -> shipped (dikirim)
     *  - shipped -> delivered (selesai)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();
        $store = $user->store;

        if (! $store || $order->store_id !== $store->id) {
            abort(403, 'Unauthorized.');
        }

        // validasi input
        $request->validate([
            'status' => [
                'required',
                Rule::in(['processing','shipped','delivered'])
            ],
            'resi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $newStatus = $request->input('status');

        // check allowed transition
        $current = $order->status;

        $allowed = false;
        // from payment_validated -> processing
        if ($current === 'payment_validated' && $newStatus === 'processing') $allowed = true;
        // processing -> shipped
        if ($current === 'processing' && $newStatus === 'shipped') $allowed = true;
        // shipped -> delivered
        if ($current === 'shipped' && $newStatus === 'delivered') $allowed = true;

        if (! $allowed) {
            return back()->with('error', "Transisi status tidak diizinkan dari `{$current}` ke `{$newStatus}`.");
        }

        // handle resi upload jika ada
        if ($request->hasFile('resi')) {
            $path = $request->file('resi')->store('resi', 'public');
            $order->resi = $path;
        }

        $order->status = $newStatus;

        // jika status delivered, juga set completed/delivery time (opsional)
        if ($newStatus === 'delivered') {
            $order->delivered_at = now();
        }

        $order->save();

        // opsional: notif ke user / admin via event/notification (tidak di-include di sini)

        return back()->with('success', "Status pesanan berhasil diubah menjadi `{$newStatus}`.");
    }

}
