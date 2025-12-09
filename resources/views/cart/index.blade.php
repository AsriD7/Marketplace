@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Keranjang Belanja</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($items->isEmpty())
        <div class="card p-4">
            <p>Keranjang Anda kosong.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Lihat Produk</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th style="width:150px">Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td style="vertical-align:middle;">
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->gambar)
                                        <img src="{{ asset('storage/'.$item->product->gambar) }}" style="width:64px;height:64px;object-fit:cover" class="me-3 rounded">
                                    @endif
                                    <div>
                                        <div>{{ $item->product->nama ?? 'Produk tidak ditemukan' }}</div>
                                        <small class="text-muted">{{ $item->product->category->nama ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td style="vertical-align:middle;">Rp {{ number_format($item->harga_saat_ini,0,',','.') }}</td>
                            <td style="vertical-align:middle;">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="qty" min="1" value="{{ $item->qty }}" class="form-control form-control-sm me-2" style="width:80px;">
                                    <button class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td style="vertical-align:middle;">Rp {{ number_format($item->qty * $item->harga_saat_ini,0,',','.') }}</td>
                            <td style="vertical-align:middle;">
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan keranjang?');">
                    @csrf
                    <button class="btn btn-outline-danger">Kosongkan Keranjang</button>
                </form>
            </div>

            <div>
                <h5>Total: Rp {{ number_format($total,0,',','.') }}</h5>
                <a href="{{ route('checkout.show') ?? url('/checkout') }}" class="btn btn-success">Checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
