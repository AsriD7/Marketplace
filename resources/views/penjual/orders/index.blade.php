@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Pesanan Masuk</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    @if($orders->isEmpty())
        <div class="card p-4">Belum ada pesanan untuk toko Anda.</div>
    @else
        <div class="list-group">
            @foreach($orders as $order)
                <a href="{{ route('penjual.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>#{{ $order->order_number }}</strong>
                            <div class="small text-muted">Pelanggan: {{ $order->user->name ?? '-' }}</div>
                            <div class="small text-muted">Total: Rp {{ number_format($order->total,0,',','.') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $order->status }}</div>
                            <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
