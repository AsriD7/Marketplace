<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        @include('layout.nav')
    </header>

        @yield('content')

    <footer>
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>