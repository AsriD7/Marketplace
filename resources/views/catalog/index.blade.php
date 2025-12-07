@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="row">
        {{-- SIDEBAR KATEGORI + FILTER --}}
        <aside class="col-md-3">
            <div class="card mb-3 p-3">
                <form method="GET" action="{{ route('catalog.index') }}">
                    <div class="mb-3">
                        <label class="form-label">Pencarian</label>
                        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari produk...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ (isset($categorySlug) && $categorySlug == $cat->slug) ? 'selected':'' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="price_min" class="form-control" placeholder="Min" value="{{ $priceMin ?? '' }}">
                            <input type="number" name="price_max" class="form-control" placeholder="Max" value="{{ $priceMax ?? '' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="">Terbaru</option>
                            <option value="price_asc" {{ (isset($sort) && $sort=='price_asc')? 'selected':'' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price_desc" {{ (isset($sort) && $sort=='price_desc')? 'selected':'' }}>Harga: Tinggi ke Rendah</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary">Terapkan</button>
                    </div>
                </form>
            </div>

            <div class="card p-3">
                <h6>Kategori</h6>
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('catalog.index') }}" class="{{ empty($categorySlug) ? 'fw-bold':'' }}">Semua</a></li>
                    @foreach($categories as $cat)
                        <li class="mt-2">
                            <a href="{{ route('catalog.category', $cat->slug) }}" class="{{ (isset($categorySlug) && $categorySlug==$cat->slug)?'fw-bold':'' }}">
                                {{ $cat->nama }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- PRODUCT GRID --}}
        <main class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Katalog Produk</h4>
                <div>
                    <small class="text-muted">Menampilkan {{ $products->total() }} produk</small>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="card p-4">
                    <p>Tidak ada produk sesuai filter.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100">
                                @if($product->gambar)
                                    <img src="{{ asset('storage/'.$product->gambar) }}" class="card-img-top" style="height:200px;object-fit:cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                        <span class="text-muted">No image</span>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title mb-1">{{ Str::limit($product->nama, 60) }}</h6>
                                    <p class="text-muted mb-1">Rp {{ number_format($product->harga,0,',','.') }}</p>
                                    <p class="text-muted small mb-2">Toko: {{ $product->store->nama_toko ?? '-' }}</p>
                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">Detail</a>

                                        @auth
                                            @if(auth()->user()->role === 'pelanggan')
                                                <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="qty" value="1">
                                                    <button class="btn btn-sm btn-success">Tambah ke Keranjang</button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-success">Login untuk Beli</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
