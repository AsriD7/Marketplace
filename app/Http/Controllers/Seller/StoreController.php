<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


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
            'nama_toko' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat_toko' => 'nullable|string',
            'jam_operasional' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048'
        ]);

        $store = auth()->user()->store;
        if (!$store) {
            $store = auth()->user()->store()->create([]);
        }

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('stores','public');
            $store->gambar = $path;
        }

        $store = auth()->user()->store ?? auth()->user()->store()->create([]);

        $store->fill($request->only(['nama_toko','deskripsi','alamat_toko','jam_operasional']));
        $store->save();

        return redirect()->route('penjual.store.index')->with('success','Informasi toko diperbarui');
    }

}
