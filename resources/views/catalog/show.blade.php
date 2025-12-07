@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            @if($product->gambar)
                <img src="{{ asset('storage/'.$product->gambar) }}" class="img-fluid">
            @else
                <div class="bg-light" style="height:400px;"></div>
            @endif
        </div>
        <div class="col-md-6">
            <h3>{{ $product->nama }}</h3>
            <p class="text-muted">Kategori: {{ $product->category->nama ?? '-' }}</p>
            <p class="mb-1">Toko: <a href="#">{{ $product->store->nama_toko ?? '-' }}</a></p>
            <h4 class="text-success">Rp {{ number_format($product->harga,0,',','.') }}</h4>
            <p>Stok: {{ $product->stok }}</p>

            <div class="mb-3">
                <strong>Rating:</strong>
                <span class="text-warning">{{ $averageRating ?? '0' }}</span> / 5
                <small class="text-muted">({{ $product->ratings()->count() }} ulasan)</small>
            </div>

            <p>{{ $product->deskripsi }}</p>

            @auth
                @if(auth()->user()->role === 'pelanggan')
                    <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-2 d-flex gap-2 align-items-center">
                            <input type="number" name="qty" value="1" min="1" max="{{ $product->stok }}" class="form-control" style="width:120px;">
                            <button class="btn btn-success">Tambah ke Keranjang</button>
                        </div>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-success">Login untuk membeli</a>
            @endauth

            <a href="{{ route('catalog.index') }}" class="btn btn-secondary">Kembali ke Katalog</a>
        </div>
    </div>
</div>
@endsection
