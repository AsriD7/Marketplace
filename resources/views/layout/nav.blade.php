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

            {{-- ================= MENU KIRI ================= --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- BERANDA --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                </li>

                {{-- KULINER --}}
                <li class="nav-item">
                    <a class="nav-link" href="">Kuliner</a>
                </li>

                {{-- ================= MENU SAAT SUDAH LOGIN SEBAGAI PELANGGAN ================= --}}
                @auth
                    @if(Auth::user()->role === 'pelanggan')
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                Pesanan Saya
                            </a>
                        </li>
                    @endif
                @endauth

            </ul>

            {{-- ================= MENU KANAN: LOGIN / AKUN ================= --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                {{-- Jika belum login --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                {{-- Jika sudah login --}}
                @auth
                    {{-- Dropdown User --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            
                            {{-- Profil pelanggan --}}
                            @if(Auth::user()->role === 'pelanggan')
                                <li>
                                    <a class="dropdown-item" href="{{ route('pelanggan.profil.index') }}">
                                        Profil Saya
                                    </a>
                                </li>
                            @endif

                            {{-- Logout --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
