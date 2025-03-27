<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\JobDraft;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class SignatureController extends Controller
{
    public function storeGdrive(Request $request, FileController $fileController)
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'nullable|file|max:2048',
                'image_url' => 'nullable|string',
                'new_signature_pad' => 'nullable|string',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $filePath = public_path('uploads/') . $fileName;
                $file->move(public_path('uploads'), $fileName);

                $fileRecord = File::create([
                    'description' => $fileName,
                    'path' => 'uploads/' . $fileName,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);

                // Create signature record and associate with file
                $signature = Signature::create([
                    'description' => 'Signature for ' . Auth::user()->name,
                    'file_id' => $fileRecord->id,
                ]);

                // Associate signature with user and set signature URL
                $user = Auth::user();
                $user->signature_id = $signature->id;
                $user->signature = 'uploads/' . $fileName; // Set signature URL
                $user->save();

                return response()->json(['success' => 'File uploaded successfully', 'file' => $fileRecord]);
            } elseif ($request->has('image_url')) {
                $imageUrl = $request->input('image_url');
                $localImagePath = public_path(ltrim(parse_url($imageUrl, PHP_URL_PATH), '/'));

                if (!file_exists($localImagePath)) {
                    return response()->json(['error' => 'Local image file not found'], 400);
                }

                $fileName = basename($localImagePath);
                $mimeType = mime_content_type($localImagePath);
                $fileSize = filesize($localImagePath);

                $newFileName = Str::random(20) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $newFilePath = public_path('uploads/') . $newFileName;
                copy($localImagePath, $newFilePath);

                $fileRecord = File::create([
                    'description' => $newFileName,
                    'path' => 'uploads/' . $newFileName,
                    'type' => $mimeType,
                    'size' => $fileSize,
                ]);

                // Create signature record and associate with file
                $signature = Signature::create([
                    'description' => 'Signature for ' . Auth::user()->name,
                    'file_id' => $fileRecord->id,
                ]);

                // Associate signature with user and set signature URL
                $user = Auth::user();
                $user->signature_id = $signature->id;
                $user->signature = 'uploads/' . $newFileName; // Set signature URL
                $user->save();

                return response()->json(['success' => 'Local image URL processed successfully', 'file' => $fileRecord]);
            } elseif ($request->has('new_signature_pad')) {
                $base64Data = $request->input('new_signature_pad');
                $imageData = str_replace('data:image/png;base64,', '', $base64Data);
                $decodedImage = base64_decode($imageData);

                if (!$decodedImage) {
                    return response()->json(['error' => 'Invalid Base64 data'], 400);
                }

                $fileName = Str::random(20) . '.png';
                $filePath = public_path('uploads/') . $fileName;
                file_put_contents($filePath, $decodedImage);

                $fileRecord = File::create([
                    'description' => $fileName,
                    'path' => 'uploads/' . $fileName,
                    'type' => 'image/png',
                    'size' => filesize($filePath),
                ]);

                // Create signature record and associate with file
                $signature = Signature::create([
                    'description' => 'Signature for ' . Auth::user()->name,
                    'file_id' => $fileRecord->id,
                ]);

                // Associate signature with user and set signature URL
                $user = Auth::user();
                $user->signature_id = $signature->id;
                $user->signature = 'uploads/' . $fileName; // Set signature URL
                $user->save();

                return redirect()->back();
            } else {
                return response()->json(['error' => 'No file, image_url, or new_signature_pad provided'], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'File upload/processing failed', 'message' => $e->getMessage()], 500);
        }
    }
}
