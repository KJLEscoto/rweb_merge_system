<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SignatureController extends Controller
{
    public function store(Request $request, FileController $fileController)
    {
        $data = $request->validate([
            'new_signature_pad' => 'required_without:signature_admin|string',
        ]);


        $user = User::findOrFail(auth()->user()->id);

        if ($request->new_signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->new_signature_pad);
            $decodedImage = base64_decode($image);

            if (!$decodedImage) {
                return response()->json(['error' => 'Invalid Base64 data'], 400);
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

            // Store the file using FileController
            $fileController = new FileController();
            $fileRequest = new Request(['file' => $file]);
            $file_data = $fileController->store($fileRequest);

            // Get the file ID from the storage response
            $file_id = $file_data->original['file']->id ?? null;

            if (!$file_id) {
                return response()->json(['error' => 'File storage failed'], 500);
            }

            // Create a signature model entry
            $signature_data = Signature::create([
                'description' => Auth::user()->name . "'s signature",
                'file_id' => $file_id,
            ]);

            // Assign the signature ID to the user
            $user = Auth::user();
            $user->signature_id = $signature_data->id;
            $user->signature = $imagePath; // Update image path
            $user->save();
        }


        return redirect()->back();
    }
}
