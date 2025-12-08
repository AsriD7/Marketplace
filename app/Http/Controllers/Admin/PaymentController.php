<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    //
    // List pembayaran pending
    public function pending()
    {
        $payments = Payment::with('order.user','order.store')->where('status','pending')->latest()->paginate(20);
        return view('admin.payments.pending', compact('payments'));
    }

    // Validasi pembayaran (confirm)
    public function validatePayment(Request $request, Payment $payment)
    {
        // ensure only pending
        if ($payment->status !== 'pending') {
            return back()->with('error','Pembayaran sudah diproses.');
        }

        // update payment + order
        $payment->update([
            'status' => 'confirmed',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $order = $payment->order;
        $order->update([
            'payment_status' => 'confirmed',
            'status' => 'payment_validated',
            'admin_validated_by' => auth()->id(),
            'admin_validated_at' => now(),
        ]);

        // (opsional) notifikasi ke penjual/user bisa ditambahkan di sini

        return back()->with('success','Pembayaran divalidasi.');
    }

    // Reject pembayaran (admin menolak bukti)
    public function rejectPayment(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error','Pembayaran sudah diproses.');
        }

        // optional: simpan alasan
        $request->validate(['reason' => 'nullable|string|max:1000']);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejected_reason' => $request->reason ?? null,
        ]);

        // update order back to pending or failed
        $order = $payment->order;
        $order->update([
            'payment_status' => 'failed',
            'status' => 'payment_failed',
        ]);

        return back()->with('success','Pembayaran ditolak; user harus mengupload ulang bukti.');
    }

}
