<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }

    public function edit(Request $request): View
    {
        $user = auth()->user();
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $user = $request->user();

        // Update validated attributes except the password and image.
        $user->fill($request->except(['password', 'current_password', 'password_confirmation', 'image']));

        // Reset email verification if the email has changed.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle password update
        if ($request->filled('current_password') || $request->filled('password')) {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            } else {
                return Redirect::route('profile.edit')
                    ->withErrors(['password' => 'New password and confirmation are required if you provide the current password.']);
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete old image if exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');
            $file->move($destination, $file_name);
            $user->image = 'uploads/' . $file_name; // Update image path
        }

        // Handle File Upload
        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $user->signature = 'signatures/' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $user->signature);
        }

        // Handle Signature Pad Input
        elseif ($request->signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $user->signature = 'signatures/signature_' . time() . '.png';
            file_put_contents(public_path($user->signature), base64_decode($image));
        }

        $user->save();

        return Redirect::route('profile.edit')->with('Status', 'Profile Updated Successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
