<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    use AuthorizesRequests;
    // tampilkan daftar produk milik toko seller
    public function index()
    {
        $store = auth()->user()->store;
        if (! $store) {
            return redirect()->route('penjual.store.edit')->with('error', 'Buat toko dulu sebelum menambahkan produk.');
        }

        // eager load category
        $products = $store->products()->with('category')->latest()->paginate(12);
        return view('penjual.produk.index', compact('products'));
    }

    // form buat produk baru
    public function create()
    {
        $store = auth()->user()->store;
        if (! $store) {
            return redirect()->route('penjual.store.edit')->with('error', 'Buat toko dulu sebelum menambahkan produk.');
        }

        $categories = Category::orderBy('nama')->get();
        return view('penjual.produk.create', compact('categories'));
    }

    // simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:4096',
            'is_active' => 'nullable|boolean',
        ]);

        $store = auth()->user()->store;

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
        }

        $product = $store->products()->create([
            'category_id' => $request->category_id,
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama) . '-' . Str::random(6),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $path,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // tampilkan detail produk (opsional)
    public function show(Product $produk)
    {
        // pastikan owner
        $this->authorize('view', $produk); // buat policy jika perlu
        return view('penjual.produk.show', compact('produk'));
    }

    // form edit produk
    public function edit(Product $produk)
    {
        // pastikan product milik seller
        if ($produk->store_id !== auth()->user()->store->id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $categories = Category::orderBy('nama')->get();
        return view('penjual.produk.edit', compact('produk','categories'));
    }

    // update produk
    public function update(Request $request, Product $produk)
    {
        if ($produk->store_id !== auth()->user()->store->id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|max:4096',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
            $produk->gambar = $path;
        }

        $produk->update([
            'category_id' => $request->category_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $produk->is_active,
        ]);

        return redirect()->route('penjual.produk.index')->with('success', 'Produk diperbarui');
    }

    // hapus produk
    public function destroy(Product $produk)
    {
        if ($produk->store_id !== auth()->user()->store->id && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $produk->delete();
        return redirect()->route('penjual.produk.index')->with('success', 'Produk dihapus');
    }

}
