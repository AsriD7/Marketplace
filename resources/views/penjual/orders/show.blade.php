@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Detail Pesanan #{{ $order->order_number }}</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card mb-3 p-3">
        <p><strong>Pelanggan:</strong> {{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '' }})</p>
        <p><strong>Alamat Kirim:</strong> {{ $order->alamat_kirim }}</p>
        <p><strong>Status:</strong> <span class="badge bg-info">{{ $order->status }}</span></p>
        <p><strong>Payment status:</strong> {{ $order->payment_status }}</p>
        @if($order->resi)
            <p><strong>Resi:</strong> <a href="{{ asset('storage/'.$order->resi) }}" target="_blank">Lihat Resi</a></p>
        @endif
    </div>

    <div class="card mb-3 p-3">
        <h5>Items</h5>
        <table class="table mb-0">
            <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($order->items as $it)
                <tr>
                    <td>{{ $it->product->nama ?? 'Produk hilang' }}</td>
                    <td>{{ $it->qty }}</td>
                    <td>Rp {{ number_format($it->harga,0,',','.') }}</td>
                    <td>Rp {{ number_format($it->qty * $it->harga,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Form update status / upload resi --}}
    <div class="card p-3 mb-3">
        <h5>Ubah Status & Upload Resi</h5>
        <form action="{{ route('penjual.orders.updateStatus', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Pilih Status</label>
                <select name="status" class="form-select" required>
                    {{-- Tampilkan pilihan yang diizinkan berdasarkan current status --}}
                    @if($order->status === 'payment_validated')
                        <option value="processing">processing (Sedang diproses)</option>
                    @elseif($order->status === 'processing')
                        <option value="shipped">shipped (Dikirim)</option>
                    @elseif($order->status === 'shipped')
                        <option value="delivered">delivered (Selesai)</option>
                    @else
                        <option value="">Tidak ada transisi yang tersedia</option>
                    @endif
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Resi (opsional)</label>
                <input type="file" name="resi" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">Format: jpg/png/pdf. Maks 5MB.</small>
            </div>

            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('penjual.orders.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
