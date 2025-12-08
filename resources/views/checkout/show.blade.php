@extends('layout.app')

@section('content')
<div class="container py-4">
    <h3>Checkout</h3>

    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="card mb-3 p-3">
            <h5>Alamat Pengiriman</h5>
            <div class="mb-2">
                <textarea name="alamat_kirim" class="form-control" rows="2" required>{{ old('alamat_kirim', $alamat_default) }}</textarea>
            </div>
            <div class="mb-2">
                <input type="text" name="catatan" class="form-control" placeholder="Catatan untuk penjual (opsional)">
            </div>
        </div>

        {{-- Groups per store --}}
        @foreach($groups as $storeId => $items)
            @php
                $store = $items->first()->product->store ?? null;
                $subtotal = $items->sum(fn($i) => $i->qty * $i->harga_saat_ini);
            @endphp

            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $store->nama_toko ?? 'Toko Tidak Diketahui' }}</h5>

                    <table class="table mb-0">
                        <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach($items as $it)
                                <tr>
                                    <td>{{ $it->product->nama ?? 'Produk hilang' }}</td>
                                    <td>{{ $it->qty }}</td>
                                    <td>Rp {{ number_format($it->harga_saat_ini,0,',','.') }}</td>
                                    <td>Rp {{ number_format($it->qty * $it->harga_saat_ini,0,',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div><small class="text-muted">Ongkir: - (belum diatur)</small></div>
                        <div><strong>Subtotal: Rp {{ number_format($subtotal,0,',','.') }}</strong></div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">Kembali ke Keranjang</a>
            <button class="btn btn-success" type="submit">Konfirmasi & Buat Pesanan</button>
        </div>
    </form>
</div>
@endsection
