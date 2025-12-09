@csrf
<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" value="{{ old('nama', $category->nama ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Slug (opsional)</label>
    <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" class="form-control">
    <small class="text-muted">Jika dikosongkan, slug akan dibuat otomatis.</small>
</div>

<div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
</div>
