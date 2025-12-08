<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    //
    /**
     * Upload bukti pembayaran untuk sebuah order.
     * Hanya user pemilik order yang boleh upload.
     */
    public function upload(Request $request, Order $order)
    {
        $user = auth()->user();

        // Pastikan pemilik order
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        // Pastikan order dalam status yang memungkinkan upload
        if (! in_array($order->payment_status, ['pending','failed'])) {
            return back()->with('error', 'Order tidak dalam status yang membutuhkan pembayaran.');
        }

        // Validasi file
        $request->validate([
            'metode' => 'required|string|max:100',
            'bukti'  => 'required|image|max:5120' // max 5MB
        ]);

        DB::beginTransaction();
        try {
            // Simpan file
            $path = $request->file('bukti')->store('payments', 'public');

            // Insert payment record (atau update jika sudah ada)
            $payment = Payment::create([
                'order_id' => $order->id,
                'metode'   => $request->metode,
                'amount'   => $order->total,
                'bukti'    => $path,
                'status'   => 'pending', // pending until admin validates
            ]);

            // Update order status to pending validation
            $order->update([
                'status' => 'paid_pending_validation',
                'payment_status' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu validasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            // hapus file jika ada
            if (!empty($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Upload gagal: ' . $e->getMessage());
        }
    }

}
