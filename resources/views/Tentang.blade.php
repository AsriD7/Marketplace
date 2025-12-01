@extends('layout.app')

@section('title', 'Tentang Kami')

@section('konten' )
<div class="container">
    <h1>Tentang Kami</h1>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    <p>Ini adalah halaman tentang kami.</p>
</div>

@endsection
