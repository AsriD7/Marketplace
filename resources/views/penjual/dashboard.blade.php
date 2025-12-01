@extends('layout.app')
@section('title', 'Dashboard Penjual')
@section('content')
<div class="container mt-5"></div>
    <h1 class="text-center mb-4">Dashboard Penjual</h1>
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