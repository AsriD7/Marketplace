@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <h5 class="mb-3">Edit Profil</h5>

                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3 text-center">
                        @php
                            $avatar = optional($profile)->avatar;
                            $avatarUrl = $avatar ? asset('storage/'.$avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D6EFD&color=fff&size=128';
                        @endphp
                        <img id="avatarPreview" src="{{ $avatarUrl }}" class="rounded-circle mb-2" style="width:120px;height:120px;object-fit:cover;">
                        <div>
                            <label class="btn btn-sm btn-outline-secondary mt-2">
                                Pilih Avatar <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">Format: jpg/png/webp, max 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $profile->alamat ?? '') }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $profile->telepon ?? '') }}" class="form-control">
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Simpan</button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatar')?.addEventListener('change', function(e){
    const [file] = this.files;
    if (!file) return;
    const url = URL.createObjectURL(file);
    document.getElementById('avatarPreview').src = url;
});
</script>
@endsection
