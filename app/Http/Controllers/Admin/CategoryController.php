<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::orderBy('nama')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:191|unique:categories,nama',
            'slug' => 'nullable|string|max:191|unique:categories,slug',
            'deskripsi' => 'nullable|string|max:2000',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['nama']);
        // ensure unique slug (append number if exists)
        $base = $data['slug'];
        $i = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:191|unique:categories,nama,' . $category->id,
            'slug' => 'nullable|string|max:191|unique:categories,slug,' . $category->id,
            'deskripsi' => 'nullable|string|max:2000',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['nama']);
        $base = $data['slug'];
        $i = 1;
        while (Category::where('slug', $data['slug'])->where('id','!=',$category->id)->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        // jika ingin mencegah hapus bila ada produk, uncomment check dibawah
        // if ($category->products()->exists()) {
        //     return back()->with('error','Kategori memiliki produk, hapus/move produk terlebih dahulu.');
        // }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}



