<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Marketplace Kuliner Mandar')</title>

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f9fafb;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        main { min-height: 80vh; }
        footer { font-size: 0.85rem; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- NAVBAR TIAP ROLE --}}
    @yield('navbar')

    {{-- KONTEN HALAMAN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-top bg-white py-3 mt-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <span class="text-muted">
                &copy; {{ date('Y') }} Marketplace Kuliner Mandar.
            </span>
            <span class="text-muted">
                Dibuat untuk tugas kampus 😊
            </span>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')
</body>
</html>
