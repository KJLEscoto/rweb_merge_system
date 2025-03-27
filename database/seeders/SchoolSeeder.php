<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            echo "Transaction started.\n";

            $fileController = new FileController();
            echo "FileController initialized.\n";

            $schoolsData = [
                ['description' => 'STI College Davao', 'image' => '/resources/img/logos/sti.png'],
                ['description' => 'Ateneo de Davao University', 'image' => '/resources/img/logos/addu.png'],
                ['description' => 'University of Mindanao', 'image' => '/resources/img/logos/um.png'],
                ['description' => 'Holy Cross of Davao College', 'image' => '/resources/img/logos/hcdc.png'],
                ['description' => 'RWeb Solutions, Corp.', 'image' => '/resources/img/logos/rweb.png'],
            ];

            $schoolsToInsert = [];

            foreach ($schoolsData as $school) {
                echo "Processing school: " . $school['description'] . "\n";
                $imagePath = public_path($school['image']); // Ensure correct path

                if (file_exists($imagePath)) {
                    echo "Image file found at: " . $imagePath . "\n";
                    // Create an UploadedFile instance
                    $imageFile = new UploadedFile(
                        $imagePath,
                        basename($imagePath),
                        mime_content_type($imagePath), // Get correct MIME type
                        null, // Error value (null means no error)
                        true // Set as test file (prevents move issues)
                    );

                    // Check if the file is valid
                    if (!$imageFile->isValid()) {
                        echo "Image file is invalid. Skipping.\n";
                        continue; // Skip this file if invalid
                    }

                    // Create a request object
                    $request = new Request(['file' => $imageFile]);

                    // Store the file using FileController
                    $fileResponse = $fileController->store($request);
                    echo "FileController store called.\n";

                    // Handle response
                    if ($fileResponse instanceof JsonResponse) {
                        $fileRecord = $fileResponse->getData();
                        echo "FileController response is JsonResponse.\n";
                    } else {
                        $fileRecord = $fileResponse;
                        echo "FileController response is not JsonResponse.\n";
                    }

                    // Ensure we have a valid file path
                    if (!empty($fileRecord->file->path)) {
                        echo "File path found: " . $fileRecord->file->path . "\n";
                        $schoolsToInsert[] = [
                            'description' => $school['description'],
                            'image' => $school['image'], // Use stored file path
                            'is_featured' => 'on',
                            'file_id' => $fileRecord->file->id,
                        ];
                        echo "School data added to insert array.\n";
                    } else {
                        echo "File path is empty. Skipping.\n";
                    }
                } else {
                    echo "Image file not found at: " . $imagePath . "\n";
                }
            }

            // Insert only if data exists
            if (!empty($schoolsToInsert)) {
                DB::table('schools')->insert($schoolsToInsert);
                echo "Schools data inserted into database.\n";
            } else {
                echo "No schools data to insert.\n";
            }

            DB::commit(); // Commit transaction if everything is successful
            echo "Transaction committed.\n";
        } catch (Exception $e) {
            echo "Exception caught: " . $e->getMessage() . "\n";
            DB::rollBack(); // Rollback transaction if there's an error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
