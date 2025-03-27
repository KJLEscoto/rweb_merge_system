<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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

        return view('admin.smm.profile.show', compact('user'));
    }

    public function edit(Request $request): View
    {
        $user = auth()->user();
        return view('admin.smm.profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateGdrive(ProfileUpdateRequest $request, FileController $fileController): RedirectResponse
    {

        $user = $request->user();


        // Update validated attributes except the password and image.
        $user->fill($request->except(['password', 'current_password', 'password_confirmation', 'image', 'signature_pad']));

        // Reset email verification if the email has changed.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle password update
        if ($request->filled('current_password') || $request->filled('password')) {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            } else {
                return Redirect::route('admin.smm.profile.edit')
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

            $file_data = $fileController->edit(new Request(['file' => $request['image']]), File::where('id', $user->profiles->file_id)->first()->description);

            $user = Auth::user();
            $user->signatures->path = $file_data->original['data']['preview_url'];
            $user->signatures->description = $file_data->original['data']['id'];
            $user->save();

            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');
            $file->move($destination, $file_name);
            $user->image = 'uploads/' . $file_name; // Update image path
        }

        // Handle File Upload
        $file_data = null;
        if ($request->hasFile(key: 'signature')) {
            $file = $request->file('signature');

            //update the current path and description of the file table
            $file_data = $fileController->edit(new Request(['file' => $request['signature']]), File::where('id', $user->signatures->file_id)->first()->description);

            //update the user file table
            $user = Auth::user();
            $user->signatures->path = $file_data->original['data']['preview_url'];
            $user->signatures->description = $file_data->original['data']['id'];
            $user->save();

            $user->signature = 'signatures/' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $user->signature);
        }

        // Handle Signature Pad Input
        elseif ($request->signature_pad) {
            // $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            // $user->signature = 'signatures/signature_' . time() . '.png';
            // file_put_contents(public_path($user->signature), base64_decode($image));

            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $decodedImage = base64_decode($image);

            if (!$decodedImage) {
                return back()->with(['status' => 'Profile Update Unsuccessfully!']);
            }


            // Define file name and path
            $timestamp = time();
            $localFileName = 'signature_' . $timestamp . '.png';
            $localFilePath = public_path('signatures/' . $localFileName);

            // Ensure the signatures directory exists
            if (!file_exists(public_path('signatures'))) {
                mkdir(public_path('signatures'), 0777, true);
            }

            // Save the file locally
            file_put_contents($localFilePath, $decodedImage);

            // Set the correct image path for response and storage
            $imagePath = 'signatures/' . $localFileName;

            // Convert Base64 to a file object
            $tempFile = tempnam(sys_get_temp_dir(), 'signature_');
            file_put_contents($tempFile, $decodedImage);

            // Create an UploadedFile instance
            $file = new UploadedFile($tempFile, $localFileName, 'image/png', null, true);

            $file_data = $fileController->edit(new Request(['file' => $file]), File::where('id', $user->signatures->file_id)->first()->description);


            $user = Auth::user();
            $user->signatures->path = $file_data->original['data']['preview_url'];
            $user->signatures->description = $file_data->original['data']['id'];
            $user->save();
        }
        $user->save();

        return Redirect::route('admin.smm.profile.edit')->with('Status', 'Profile Updated Successfully!');
    }

    public function update(ProfileUpdateRequest $request, FileController $fileController): RedirectResponse
    {
        $user = $request->user();

        // @dd($request->all()); // Keep this for debugging if needed

        // Update validated attributes except the password, image, and signature.
        $user->fill($request->except(['password', 'current_password', 'password_confirmation', 'image', 'signature', 'signature_pad']));

        // Reset email verification if the email has changed.
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            echo "Email verification reset for user: " . $user->id . "\n";
        }

        // Handle password update
        if ($request->filled('current_password') || $request->filled('password')) {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
                echo "Password updated for user: " . $user->id . "\n";
            } else {
                echo "Password update failed for user: " . $user->id . ". New password and confirmation are required.\n";
                return Redirect::route('admin.smm.profile.edit')
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
                echo "Old image deleted for user: " . $user->id . "\n";
            }

            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');

            // Update the file table BEFORE moving the file.
            $fileController->edit(new Request(['file' => $request->file('image')]), File::where('id', $user->profiles->file_id)->first()->id);
            echo "File table updated for user image: " . $user->id . "\n";

            // Move the file AFTER updating the file table.
            $file->move($destination, $file_name);
            $user->image = 'uploads/' . $file_name; // Update image path

            // Update the profile file path.
            $user->profiles->file->path = 'uploads/' . $file_name;
            $user->profiles->file->save();
            echo "Profile file path updated for user: " . $user->id . "\n";

            // Redundancy: Update user's image URL directly
            $user->image = 'uploads/' . $file_name;
            $user->save();
            echo "User image URL updated directly for user: " . $user->id . "\n";
        }

        // Handle File Upload or Signature Pad Input for signature
        if ($request->hasFile('signature')) {

            // Update the file table
            $fileController->edit(new Request(['file' => $request->signature]), File::where('id', $user->signatures->file_id)->first()->id);
            echo "File table updated for user signature file upload: " . $user->id . "\n";

            $file = $request->file('signature');
            $user->signature = 'signatures/' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $user->signature);

            // Update the user signature path.
            $user->signatures->file->path = $user->signature;
            $user->signatures->file->save();
            echo "User signature file path updated for user: " . $user->id . "\n";

            // Redundancy: Update user's signature URL directly
            $user->signature = $user->signature;
            $user->save();
            echo "User signature URL updated directly for user: " . $user->id . "\n";
        } elseif ($request->signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $decodedImage = base64_decode($image);

            if (!$decodedImage) {
                echo "Signature pad input invalid for user: " . $user->id . "\n";
                return back()->with(['status' => 'Profile Update Unsuccessfully!']);
            }

            $timestamp = time();
            $localFileName = 'signature_' . $timestamp . '.png';
            $localFilePath = public_path('signatures/' . $localFileName);

            if (!file_exists(public_path('signatures'))) {
                mkdir(public_path('signatures'), 0777, true);
                echo "Signatures directory created for user: " . $user->id . "\n";
            }

            file_put_contents($localFilePath, $decodedImage);

            $imagePath = 'signatures/' . $localFileName;

            $tempFile = tempnam(sys_get_temp_dir(), 'signature_');
            file_put_contents($tempFile, $decodedImage);

            $file = new UploadedFile($tempFile, $localFileName, 'image/png', null, true);

            // Update the file table
            $fileController->edit(new Request(['file' => $file]), File::where('id', $user->signatures->file_id)->first()->id); //Changed file to signature
            echo "File table updated for user signature pad input: " . $user->id . "\n";

            // Update the signature path.
            $user->signatures->file->path = $imagePath;
            $user->signatures->file->save();
            echo "User signature pad file path updated for user: " . $user->id . "\n";

            // Redundancy: Update user's signature URL directly
            $user->signature = $imagePath;
            $user->save();
            echo "User signature pad URL updated directly for user: " . $user->id . "\n";
        }

        $user->save();
        echo "User profile updated successfully for user: " . $user->id . "\n";

        return Redirect::route('admin.smm.profile.edit')->with('Status', 'Profile Updated Successfully!');
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
