<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Store;


class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = auth()->user()->store;
        return view('penjual.store.index', compact('store'));
    }

    public function edit()
    {
        $store = auth()->user()->store;
        return view('penjual.store.edit', compact('store'));
    }

    public function update(Request $request)
{
    $request->validate([
        'nama_toko'       => 'required|string|max:255',
        'deskripsi'       => 'nullable|string',
        'alamat_toko'     => 'nullable|string',
        'jam_operasional' => 'nullable|string',
        'gambar'          => 'nullable|image|max:2048',
    ]);

    $user  = auth()->user();
    $store = $user->store; // bisa null kalau belum ada

    // Ambil data dari form
    $data = $request->only([
        'nama_toko',
        'deskripsi',
        'alamat_toko',
        'jam_operasional',
    ]);

    $data['slug'] = Str::slug($request->nama_toko);

    // Jika ada upload gambar, simpan path-nya
    if ($request->hasFile('gambar')) {
        $path          = $request->file('gambar')->store('stores', 'public');
        $data['gambar'] = $path;
    }

    // Kalau store belum ada -> buat baru pakai $data (sudah ada nama_toko)
    if (!$store) {
        $store = $user->store()->create($data);
    } else {
        // Kalau sudah ada -> update
        $store->update($data);
    }

    return redirect()
        ->route('penjual.store.index')
        ->with('success', 'Informasi toko diperbarui');
}


}
