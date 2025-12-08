@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Riwayat Pesanan</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    @if($orders->isEmpty())
        <div class="card p-4">
            <p>Belum ada pesanan.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">Lihat Produk</a>
        </div>
    @else
        <div class="list-group">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">#{{ $order->order_number }}</h5>
                        <small>{{ $order->created_at->format('d M Y H:i') }}</small>
                    </div>
                    <p class="mb-1">Toko: {{ $order->store->nama_toko ?? '-' }} — Total: Rp {{ number_format($order->total,0,',','.') }}</p>
                    <small>Status: {{ $order->status }} / Pembayaran: {{ $order->payment_status }}</small>
                </a>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
