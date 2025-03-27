<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Get Google OAuth Token.
     */
    public function token()
    {
        $client_id = config('services.google.client_id');
        $client_secret = config('services.google.client_secret');
        $refresh_token = config('services.google.refresh_token');

        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        return json_decode((string)$response->getBody(), true)['access_token'] ?? null;
    }

    /**
     * Store a file in Google Drive.
     */
    public function storeGdrive(Request $request)
    {
        try {
            DB::beginTransaction();


            $file_data = $request->validate([
                'file' => 'nullable|file', // File input is optional
                'image_url' => 'nullable|url', // Allow image URLs
            ]);



            $accessToken = $this->token();
            if (!$accessToken) {
                return response()->json(['error' => 'Failed to retrieve access token'], 500);
            }

            $file = null;
            $fileName = null;
            $fileSize = null;
            $mimeType = null;

            if ($request['file'] != null) {
                $file = $request['file'];
                $fileExtension = $file->getClientOriginalExtension(); // Get original file extension
                $fileName = Str::random(20) . '.' . $fileExtension; // Generate random name with extension
                $fileSize = $file->getSize();
                $mimeType = $file->getClientMimeType();
            }
            // **Handle image URL**
            elseif ($request->image_url) {
                $imageUrl = $request->image_url;

                // Extract filename from URL
                $fileName = basename(parse_url($imageUrl, PHP_URL_PATH));
                $tempFilePath = storage_path('app/temp_' . $fileName);

                // Initialize cURL session
                $ch = curl_init($imageUrl);
                $fp = fopen($tempFilePath, 'wb'); // Open file to save the downloaded content

                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Adjust based on your security needs
                curl_setopt($ch, CURLOPT_FAILONERROR, true);

                $success = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                fclose($fp);

                if (!$success || $httpCode !== 200) {
                    unlink($tempFilePath); // Remove the incomplete file
                    throw new \Exception("Failed to download image from URL.");
                }

                // Get MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $tempFilePath);
                finfo_close($finfo);

                $fileSize = filesize($tempFilePath);
                $file = new \Illuminate\Http\File($tempFilePath); // Convert to Laravel file
            } else {
                return response()->json(['error' => 'No file or image URL provided!'], 400);
            }

            $folderId = config('services.google.folder_id');


            // Step 1: Create metadata
            $metadata = [
                'name' => $fileName,
                'mimeType' => $mimeType,
                'parents' => [$folderId],
            ];

            // Step 2: Upload file in multipart request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'multipart/related; boundary=foo_bar_baz',
            ])->send('POST', 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart', [
                'body' => "--foo_bar_baz\r\n" .
                    "Content-Type: application/json; charset=UTF-8\r\n\r\n" .
                    json_encode($metadata) . "\r\n" .
                    "--foo_bar_baz\r\n" .
                    "Content-Type: " . $mimeType . "\r\n\r\n" .
                    file_get_contents($file->path()) . "\r\n" .
                    "--foo_bar_baz--",
            ]);

            // Convert response to JSON
            $data = $response->json();


            if (!isset($data['id'])) {
                throw new \Exception("Upload failed! Response: " . json_encode($data));
            }

            $fileId = $data['id'];

            // // Generate Google Drive direct file link
            // $fileLink = "https://drive.google.com/uc?id=" . $fileId;
            // Generate the new Google Drive direct image link format
            $fileLink = "https://lh3.googleusercontent.com/d/{$fileId}";

            // Store in database
            $fileRecord = File::create([
                'description' => $fileId,
                'path' => $fileLink, // Store the direct link
                'type' => $mimeType,
                'size' => $fileSize,
            ]);

            // Clean up temp file
            if (isset($tempFilePath) && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }

            DB::commit();

            return response()->json(['success' => 'File uploaded successfully', 'file' => $fileRecord]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'File upload failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'nullable|file|max:2048', // Adjust max file size as needed
                'image_url' => 'nullable|string',
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file'); // Use $request->file('file')
                $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                // Store file information in the database
                $fileRecord = File::create([
                    'description' => $fileName, // Or a more descriptive name
                    'path' => 'uploads/' . $fileName, // Store the relative path
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
                $filePath = public_path('uploads') . '/' . $fileName;


                // Move the uploaded file to the public/uploads directory
                $file->move(public_path('uploads'), $fileName);


                return response()->json(['success' => 'File uploaded successfully', 'file' => $fileRecord]);
            } elseif ($request->has('image_url')) {
                $imageUrl = $request->input('image_url');

                // Validate that the image_url is a local file path
                $localImagePath = public_path(ltrim(parse_url($imageUrl, PHP_URL_PATH), '/'));

                if (!file_exists($localImagePath)) {
                    return response()->json(['error' => 'Local image file not found'], 400);
                }

                // Get file information
                $fileName = basename($localImagePath);
                $mimeType = mime_content_type($localImagePath);
                $fileSize = filesize($localImagePath);

                // Copy the local file to the uploads directory
                $newFileName = Str::random(20) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $newFilePath = public_path('uploads/') . $newFileName;
                copy($localImagePath, $newFilePath);

                // Store file information in the database
                $fileRecord = File::create([
                    'description' => $newFileName,
                    'path' => 'uploads/' . $newFileName,
                    'type' => $mimeType,
                    'size' => $fileSize,
                ]);

                return response()->json(['success' => 'Local image URL processed successfully', 'file' => $fileRecord]);
            } else {
                return response()->json(['error' => 'No file or image_url provided'], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'File upload/processing failed', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Retrieve list of files from Google Drive.
     */
    public function retrieve()
    {
        return $this->index();
    }

    /**
     * Get all files from Google Drive.
     */
    public function index()
    {
        $accessToken = $this->token();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        $folderId = config('services.google.folder_id');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://www.googleapis.com/drive/v3/files?q='" . $folderId . "' in parents&fields=files(id,name,mimeType,webViewLink,createdTime)");

        $result = json_decode($response->body(), true);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to retrieve files', 'details' => $result], $response->status());
        }

        return response()->json([
            'files' => $result['files'] ?? [],
        ]);
    }

    /**
     * Edit/Rename a file in Google Drive.
     */
    public function editGdrive(Request $request, $fileId)
    {
        $data = $request->validate([
            'file' => 'required|file', // Ensure a file is provided
        ]);


        $accessToken = $this->token();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        $file = $request['file'] ?? null;

        if (!$file instanceof \Illuminate\Http\UploadedFile) {
            return response()->json(['error' => 'Invalid file upload'], 400);
        }


        if (!$file || !$file->isValid()) {
            return response()->json(['error' => 'Uploaded file is not valid'], 400);
        }

        $filePath = $file->getPathname();
        $fileMimeType = $file->getClientMimeType();
        $fileName = $file->getClientOriginalName();

        // Extract fileId from Google Drive URL if needed
        if (isset($request->file_id)) {
            $fileId = $this->extractDriveFileId($request->file_id);
        }

        if (!isset($fileId) || empty($fileId)) {
            return response()->json(['error' => 'No valid File ID found'], 400);
        }

        $metadata = json_encode(['name' => $fileName]);

        // Upload the updated file
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->attach(
            'metadata',
            $metadata,
            'metadata.json'
        )->attach(
            'file',
            file_get_contents($filePath),
            $fileName,
            [
                'Content-Type' => $fileMimeType,
            ]
        )->patch("https://www.googleapis.com/upload/drive/v3/files/{$fileId}?uploadType=multipart");

        $result = json_decode($response->body(), true);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to update file', 'details' => $result], $response->status());
        }

        // Fetch the updated file metadata to get preview links
        $fileInfo = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://www.googleapis.com/drive/v3/files/{$fileId}?fields=id,name,mimeType,webViewLink,webContentLink");

        $fileData = json_decode($fileInfo->body(), true);

        return response()->json([
            'message' => 'File updated successfully',
            'data' => [
                'id' => $fileData['id'],
                'name' => $fileData['name'],
                'mimeType' => $fileData['mimeType'],
                'preview_url' => isset($fileData['webViewLink'])
                    ? preg_replace('/drive\.google\.com\/file\/d\/(.*?)\/view.*/', 'lh3.googleusercontent.com/d/$1=s220', $fileData['webViewLink'])
                    : null,
                'download_url' => $fileData['webContentLink'] ?? null, // Direct download link
            ]
        ]);
    }

    public function edit(Request $request, $fileId)
    {
        try {
            $request->validate([
                'file' => 'required|file',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors()); // Inspect validation errors
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        }

        //$file = $request->file('file'); // Use $request->file('file') - correct for modern Laravel
        $file = $request->file;

        if (!$file) {
            // File is not present, do not proceed
            dd('File not found using $request->file()', $request->all()); // Corrected message
        }

        // dd($file, $request->all()); // Debugging after defining $file - redundant, moved above

        if (!$file->isValid()) {
            return response()->json(['error' => 'Uploaded file is not valid'], 400);
        }

        // Find the existing file record
        $existingFile = File::find($fileId);

        if (!$existingFile) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Delete the old file from storage
        if (Storage::exists($existingFile->path)) {
            Storage::delete($existingFile->path);
        }

        // Store the new file
        $path = $file->store('uploads'); // Store in the 'uploads' directory

        // Update the file record in the database
        $existingFile->update([
            'path' => $path,
            'description' => $file->getClientOriginalName(), // Update description
        ]);

        return response()->json([
            'message' => 'File updated successfully',
            'data' => [
                'id' => $existingFile->id,
                'name' => basename($path), // Return the new name
                'description' => $file->getClientOriginalName(),
                'mimeType' => $existingFile->type,
                'path' => $existingFile->path,
                'size' => $existingFile->size,
            ],
        ]);
    }

    function extractDriveFileId($url)
    {
        if (!is_string($url) || empty($url)) {
            return null; // Return null if $url is not a valid string
        }

        $patterns = [
            '/\/d\/([a-zA-Z0-9_-]+)/', // Matches "/d/FILE_ID"
            '/id=([a-zA-Z0-9_-]+)/',   // Matches "?id=FILE_ID"
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1]; // Return the extracted file ID
            }
        }

        return null; // Return null if no match is found
    }
    /**
     * Delete a file from Google Drive.
     */
    public function delete($fileId)
    {
        $accessToken = $this->token();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->delete("https://www.googleapis.com/drive/v3/files/{$fileId}");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to delete file'], $response->status());
        }

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function view($fileId)
    {
        $accessToken = $this->token();
        if (!$accessToken) {
            return response()->json(['error' => 'Failed to retrieve access token'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://www.googleapis.com/drive/v3/files/{$fileId}?fields=webViewLink,webContentLink,permissions");

        $result = json_decode($response->body(), true);

        if ($response->failed() || !isset($result['webViewLink'])) {
            return response()->json(['error' => 'Failed to retrieve file link', 'details' => $result], $response->status());
        }

        // If a direct webContentLink is available, use it
        $fileUrl = $result['webContentLink'] ?? "https://drive.google.com/uc?export=view&id={$fileId}";

        return response()->json(['url' => $fileUrl]);
    }
}
