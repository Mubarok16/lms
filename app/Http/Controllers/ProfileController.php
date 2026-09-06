<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function edit_lecturer(Request $request): View
    {
        return view('profile.edit-lecturer', [
            'user' => $request->user(),
        ]);
    }
    public function edit_admin(Request $request): View
    {
        return view('profile.edit-admin', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Update the user's profile information.
     */

    // UPDATE PHOTO PROFIL
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('profile_photo')) {

            // Hapus foto lama jika ada
            if (
                $user->profile_photo &&
                Storage::disk('public')->exists($user->profile_photo)
            ) {

                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    public function update_lecturer(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('profile_photo')) {

            // Hapus foto lama jika ada
            if (
                $user->profile_photo &&
                Storage::disk('public')->exists($user->profile_photo)
            ) {

                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('lecturer.profile.edit')
            ->with('status', 'profile-updated');
    }

    public function update_admin(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('profile_photo')) {

            // Hapus foto lama jika ada
            if (
                $user->profile_photo &&
                Storage::disk('public')->exists($user->profile_photo)
            ) {

                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')
                ->store('profile-photos', 'public');

            $user->profile_photo = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('admin.profile.edit')
            ->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
