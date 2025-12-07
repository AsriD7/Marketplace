@extends('layout.app')
@section('content')
    {{-- =========================================
         LANDING PAGE PENGGUNA - KULINER MANDAR
         Role: Pelanggan / Pengguna biasa
       ========================================== --}}

    <style>
        /* Bisa kamu pindah ke file CSS terpisah */
        .hero-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #ffffff;
        }

        .hero-title {
            font-size: 2.4rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            margin-top: 0.75rem;
            opacity: 0.9;
        }

        .btn-primary-custom {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            border: none;
            background-color: #f97316;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            opacity: 0.9;
        }

        .btn-outline-custom {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            border: 1px solid #ffffff;
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .card-custom {
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            padding: 1.25rem;
            height: 100%;
            background-color: #ffffff;
        }

        .badge-kategori {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            background-color: #e0f2fe;
            color: #0369a1;
            margin-bottom: 0.5rem;
        }

        .card-produk-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .price-text {
            font-weight: 700;
            color: #16a34a;
        }

        .text-muted-small {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            background-color: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .cta-section {
            padding: 2.5rem 1.5rem;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #0f172a, #1f2937);
            color: #ffffff;
            text-align: center;
        }

        .search-input {
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            padding: 0.65rem 1rem;
            width: 100%;
        }

        .search-wrapper {
            max-width: 480px;
        }
    </style>

    {{-- ===================== HERO SECTION ===================== --}}
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <h1 class="hero-title">
                        Temukan Kuliner Khas Mandar Favoritmu 🍽️
                    </h1>
                    <p class="hero-subtitle">
                        Jelajahi berbagai makanan khas Mandar: dari jepa, bau peapi, hingga kue-kue tradisional.
                        Semua UMKM lokal dalam satu marketplace.
                    </p>

                    {{-- Form Pencarian Produk --}}
                    <div class="mt-4 search-wrapper">
                        <form action="" method="GET">
                            <div class="d-flex gap-2">
                                <input type="text"
                                       name="q"
                                       class="search-input"
                                       placeholder="Cari nama makanan, kategori, atau penjual...">
                                <button type="submit" class="btn-primary-custom">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-2 text-muted-small">
                        <span>✅ UMKM Lokal Mandar</span>
                        <span>✅ Rekomendasi kategori makanan</span>
                        <span>✅ Pesan cepat & mudah</span>
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-3">
                        <a href="" class="btn-primary-custom">
                            Lihat Semua Kuliner
                        </a>
                        @auth
                            <a href="" class="btn-outline-custom">
                                Lihat Pesanan Saya
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-outline-custom">
                                Masuk untuk Mulai Pesan
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Ilustrasi / Highlight Kategori --}}
                <div class="col-md-6">
                    <div class="card-custom">
                        <div class="mb-3">
                            <span class="badge-kategori">Highlight Kategori</span>
                        </div>
                        <h5 class="mb-3">Kuliner Andalan Mandar</h5>
                        <ul class="mb-0">
                            <li><strong>Jajanan Tradisional:</strong> jepa, kui-kui, onde-onde, dan lainnya.</li>
                            <li><strong>Olahan Laut:</strong> bau peapi, ikan bakar, pallumara.</li>
                            <li><strong>Kue Basah & Kering:</strong> barongko, bolu, dan lain-lain.</li>
                            <li><strong>Minuman Segar:</strong> es sarabba, minuman khas pesisir.</li>
                        </ul>
                        <p class="mt-3 text-muted-small mb-0">
                            Setiap produk dikelola oleh pelaku UMKM lokal Mandar dengan rasa otentik dan harga terjangkau.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== KATEGORI SECTION ===================== --}}
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Jelajahi Berdasarkan Kategori</h2>
            <p class="section-subtitle">
                Pilih kategori kuliner sesuai selera kamu. Sistem bisa membantu merekomendasikan kategori makanan
                yang cocok dengan preferensi pengguna.
            </p>

            <div class="row g-3">
                {{-- Contoh kategori statis, nanti bisa di-loop dari database --}}
                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <div class="mb-2">🍞</div>
                        <h6>Jajanan Tradisional</h6>
                        <p class="text-muted-small mb-0">
                            Jepa, kui-kui, cucur, dan lainnya.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <div class="mb-2">🐟</div>
                        <h6>Olahan Laut</h6>
                        <p class="text-muted-small mb-0">
                            Bau peapi, ikan bakar, pallumara.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <div class="mb-2">🍰</div>
                        <h6>Kue & Dessert</h6>
                        <p class="text-muted-small mb-0">
                            Kue basah & kering khas Mandar.
                        </p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card-custom text-center">
                        <div class="mb-2">🥤</div>
                        <h6>Minuman</h6>
                        <p class="text-muted-small mb-0">
                            Minuman khas pesisir Mandar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== PRODUK REKOMENDASI ===================== --}}
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Rekomendasi Kuliner untuk Kamu</h2>
            <p class="section-subtitle">
                Daftar produk ini bisa diisi dari hasil rekomendasi sistem (misalnya pakai KNN / CBF) atau sekadar
                “produk populer” sementara.
            </p>

            @if(isset($produkRekomendasi) && $produkRekomendasi->count())
                <div class="row g-3">
                    @foreach($produkRekomendasi as $produk)
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="card-custom">
                                {{-- Gambar produk, fallback jika null --}}
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}"
                                         alt="{{ $produk->nama }}"
                                         class="card-produk-img">
                                @else
                                    <div class="card-produk-img d-flex align-items-center justify-content-center"
                                         style="background-color: #f3f4f6;">
                                        <span class="text-muted-small">Tidak ada gambar</span>
                                    </div>
                                @endif

                                <span class="badge-kategori">
                                    {{ $produk->kategori->nama ?? 'Kuliner Mandar' }}
                                </span>

                                <h6 class="mt-1 mb-1">
                                    {{ $produk->nama }}
                                </h6>

                                <p class="text-muted-small mb-1">
                                    {{ Str::limit($produk->deskripsi, 60) }}
                                </p>

                                <p class="price-text mb-1">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </p>

                                <p class="text-muted-small mb-2">
                                    Oleh: {{ $produk->penjual->nama_toko ?? $produk->penjual->name ?? 'UMKM Mandar' }}
                                </p>

                                <a href="{{ route('produk.show', $produk->id) }}" class="btn-primary-custom w-100 text-center">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted-small">
                    Belum ada data rekomendasi kuliner untuk ditampilkan. Silakan tambahkan produk dulu di panel penjahit / UMKM.
                </p>
            @endif
        </div>
    </section>

    {{-- ===================== CARA KERJA ===================== --}}
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Cara Kerja Marketplace Kuliner Mandar</h2>
            <p class="section-subtitle">
                Hanya dalam beberapa langkah, pengguna sudah bisa menemukan, memilih, dan memesan kuliner khas Mandar.
            </p>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card-custom h-100">
                        <div class="step-circle">1</div>
                        <h6>Buat Akun / Masuk</h6>
                        <p class="text-muted-small mb-0">
                            Daftar sebagai pengguna, lalu lengkapi profilmu untuk pengalaman rekomendasi yang lebih personal.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-custom h-100">
                        <div class="step-circle">2</div>
                        <h6>Cari & Pilih Kuliner</h6>
                        <p class="text-muted-small mb-0">
                            Telusuri kuliner berdasarkan kategori, penjual, atau gunakan fitur rekomendasi untuk menemukan
                            makanan yang cocok dengan seleramu.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-custom h-100">
                        <div class="step-circle">3</div>
                        <h6>Lakukan Pemesanan</h6>
                        <p class="text-muted-small mb-0">
                            Tambahkan ke keranjang, lakukan pemesanan, lalu tunggu konfirmasi dari penjual/UMKM yang kamu pilih.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA AKHIR ===================== --}}
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <h3 class="mb-2">Siap Menjelajahi Kuliner Mandar? 🌊</h3>
                <p class="mb-4">
                    Dukung UMKM lokal dan nikmati cita rasa khas Mandar lewat satu platform marketplace yang terintegrasi.
                </p>

                @guest
                    <a href="{{ route('register') }}" class="btn-primary-custom">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn-outline-custom ms-2">
                        Saya sudah punya akun
                    </a>
                @else
                    <a href="" class="btn-primary-custom">
                        Mulai Cari Kuliner
                    </a>
                @endguest
            </div>
        </div>
    </section>
@endsection
