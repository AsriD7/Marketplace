<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Marketplace Kuliner Mandar')</title>

    {{-- Bootstrap 5 CDN --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <style>
        body {
            background-color: #f9fafb;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .navbar-brand span {
            font-weight: 700;
        }

        .navbar {
            font-size: 0.95rem;
        }

        main {
            min-height: 80vh;
        }

        footer {
            font-size: 0.85rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- ===================== NAVBAR ===================== --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container">
            {{-- Brand / Nama Aplikasi --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <span class="me-1">🍽️</span>
                <span>Kuliner Mandar</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {{-- Menu kiri --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        {{-- Ganti route ini kalau kamu punya route khusus landing --}}
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        @if(Route::has('produk.index'))
                            <a class="nav-link" href="{{ route('produk.index') }}">Kuliner</a>
                        @endif
                    </li>

                    @auth
                        {{-- Contoh menu tambahan untuk user login --}}
                        @if(Route::has('pelanggan.pesanan.index'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pelanggan.pesanan.index') }}">
                                    Pesanan Saya
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                {{-- Menu kanan: Auth --}}
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name ?? 'Pengguna' }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                {{-- Kalau ada halaman profil, bisa tambahkan di sini --}}
                                {{-- <a class="dropdown-item" href="{{ route('profil.index') }}">Profil</a> --}}

                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- ===================== KONTEN HALAMAN ===================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="border-top bg-white py-3 mt-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <span class="text-muted">
                &copy; {{ date('Y') }} Marketplace Kuliner Mandar. All rights reserved.
            </span>
            <span class="text-muted">
                Dibuat untuk tugas RPL / Framework Web Based 😊
            </span>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>

    @stack('scripts')
</body>
</html>
