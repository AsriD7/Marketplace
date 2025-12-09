<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    //
    public function show(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        return view('profile.show', compact('user','profile'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        return view('profile.editt', compact('user','profile'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // ensure profile exists
        $profile = $user->profile ?? $user->profile()->create([]);

        // handle avatar upload
        if ($request->hasFile('avatar')) {
            // delete old file if exists
            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $path;
        }

        $profile->alamat = $data['alamat'] ?? $profile->alamat;
        $profile->telepon = $data['telepon'] ?? $profile->telepon;
        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}

