<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\User;
use App\Models\Pin;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo_path')) {
            $photo = $request->file('profile_photo_path');
            $filename = time() . '.' . $photo->extension(); // Generate a unique filename
            $photoPath = $photo->storeAs('public/profile_photos', $filename); // Store in 'public/profile_photos' directory
            $user->profile_photo_path = $photoPath; // Update the path in the database
        }

        // Update other profile fields
        $user->fill($request->except('profile_photo_path'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    public function userProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $pins = Pin::where('user_id', $user->id)->get();
        $likesCount = $user->likes->count();
        $followersCount = $user->followers->count();

        return view('profile.index', compact('user', 'pins', 'likesCount', 'followersCount'));
    }
}
