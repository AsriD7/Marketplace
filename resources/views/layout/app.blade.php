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
    
    @include('layout.nav')
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
