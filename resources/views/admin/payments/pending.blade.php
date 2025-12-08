@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Pembayaran - Pending</h3>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Order</th>
                <th>User</th>
                <th>Store</th>
                <th>Jumlah</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td><a href="{{ route('orders.show', $p->order->id) }}">{{ $p->order->order_number }}</a></td>
                <td>{{ $p->order->user->name ?? '-' }}</td>
                <td>{{ $p->order->store->nama_toko ?? '-' }}</td>
                <td>Rp {{ number_format($p->amount,0,',','.') }}</td>
                <td>
                    @if($p->bukti)
                        <img src="{{ asset('storage/'.$p->bukti) }}" style="max-width:140px;">
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.payments.validate', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Validasi</button>
                    </form>
                    <form action="{{ route('admin.payments.reject', $p->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?')">Tolak</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $payments->links() }}
</div>
@endsection
