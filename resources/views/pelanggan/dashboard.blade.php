@extends('layout.app')
@section('title', 'Dashboard Pelanggan')
@section('content')
<div class="container mt-5"></div>
    <h1 class="text-center mb-4">Dashboard Pelanggan</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm p-4">
                <h2>Selamat datang, {{ auth()->user()->name }}!</h2>
                <p>Ini adalah dashboard Anda di mana Anda dapat mengelola akun dan melihat data Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Uncomment the following section if you want to include a logout link --}}

{{-- <div>
    <!-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead -->
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
</div> --}}
