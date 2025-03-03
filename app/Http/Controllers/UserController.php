<?php

namespace App\Http\Controllers;

use App\Models\DtrDownloadRequest;
use App\Models\Histories;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HistoryController;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Mail\EmailShiftNotification;
use App\Models\File;
use App\Models\Profile;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    //this function will return all the users data in the database
    public function index()
    {
        //get all user data
        $users = User::all();

        //return the users data to the view
        return view('users.profile.index', compact('users'));
    }

    public function showSettings(FileController $fileController)
    {
        $user = Auth::user();

        $image_url = File::where('id', Profile::where('id', $user->profile_id)->value('file_id'))->first()->path;

        // $google_drive_link = $user_files_paths;
        // preg_match('/id=([a-zA-Z0-9_-]+)/', $google_drive_link, $matches);
        // $file_id = $matches[1] ?? null;

        // echo $file_id; // Outputs: 1xe5yHio0kSRdKOhwMxMxIJ_Z4AhcG_ln
        $image_url = isset($image_url)
            ? (str_contains($image_url, 'drive.google.com/uc?id=')
                ? preg_replace('/drive\.google\.com\/uc\?id=([a-zA-Z0-9_-]+)/', 'lh3.googleusercontent.com/d/$1=s220?t=' . time(), $image_url)
                : $image_url
            )
            : asset('resources/img/default-male.png');

        //https://drive.google.com/thumbnail?id=1zSM0Y_I9m5YcYwLZS-J63la5uLjxd4xQ&timestamp=12345

        return view('users.settings', [
            'user' => $user,
            'image_url' => $image_url,
        ]);
    }

    public function AdminScannerTimeCheck(Request $request, EmailController $emailController)
    {
        try {
            // Initialized the success text
            $success_text = "";

            // Get the user data from the QR code
            $userData = User::where('qr_code', $request->qr_code)->first();

            // Check if the user exists
            if (!$userData) {
                return back()->with('error', 'User not found.');
            }

            // Initialize the histories object (table)
            $timeCheck = new Histories();

            // Set the user ID
            $timeCheck->user_id = $userData->id;

            // Set the datetime (Internet global time)
            $timeCheck->datetime = Carbon::now()->timezone('Asia/Manila');

            // Validate the type of time check
            if (!in_array($request->type, ['time_in', 'time_out'])) {
                return response()->json(['success' => false, 'message' => 'Invalid time check type']);
            }

            // Define allowed time-ins per day
            $allowedTimes = [
                'Monday' => '9:15 AM',
                'Tuesday' => '8:15 AM',
                'Wednesday' => '8:15 AM',
                'Thursday' => '8:15 AM',
                'Friday' => '8:15 AM',
                'Saturday' => '8:15 AM',
            ];

            // Get current day and allowed time-in
            $currentDay = Carbon::now()->timezone('Asia/Manila')->format('l'); // Full day name (e.g., Monday)
            $allowedTimeIn = $allowedTimes[$currentDay] ?? '8:00 AM';

            // Convert allowed time-in to Carbon
            $allowedTime = Carbon::parse($allowedTimeIn, 'Asia/Manila');

            if ($request->type == 'time_in') {
                $timeCheck->description = 'time in';

                // Clone allowedTime before adding minutes
                if ($timeCheck->datetime->greaterThan($allowedTime->copy()->addMinutes(15))) {
                    $timeCheck->extra_description = 'late';
                }

                $success_text = "Time in checked successfully";
            } elseif ($request->type == 'time_out') {
                $timeCheck->description = 'time out';
                $success_text = "Time out checked successfully";
            }

            // Save the data to the database
            $timeCheck->save();

            // Send the shift notification email
            try {
                $sendShiftNotification = $emailController->EmailShiftNotification($userData, $timeCheck);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }

            // Return the success text
            return response()->json(['success' => true, 'message' => $success_text]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    public function showAdminProfile(RankingController $rankingController, HistoryController $historyController)
    {
        $user = Auth::user();

        return view('admin.dtr.profile.index', [
            'user' => $user,
        ]);
    }

    public function showAdminProfileEdit()
    {
        $user = Auth::user();

        return view('admin.dtr.profile.edit', [
            'user' => $user,
        ]);
    }

    public function showAdminUserProfile($id)
    {

        //access user with authenticated user
        $access_user = Auth::user();

        //this will find the user data with the id
        $users = User::find($id);

        //check if the role is either admin or anything else
        if ($access_user->role == 'admin') {

            //return response()->json(['users' => $users], Response::HTTP_INTERNAL_SERVER_ERROR);
            if (!(isset($users))) {
                return redirect()->route('admin.dtr.dashboard');
            }

            return view('users.profile.edit', [
                'user' => $users,
                'access_user' => $access_user,
            ]);
        } else {
            //check if the user is the same as the access user
            if ($access_user->id === $users->id) {
                return view('users.profile.edit', [
                    'user' => $users,
                    'access_user' => $access_user,
                ]);
            } else {
                return redirect()->route('users.profile.index');
            }
        }
    }

    public function AdminScannerValidation($qr_code)
    {
        try {
            $users = User::where('qr_code', $qr_code)->first();

            return response()->json([
                'user' => $users,
                'profile_image' => File::where('id', $users->profiles->file_id)->first()->path,
                'valid' => true
            ], Response::HTTP_OK);

            // return back()->with('success', 'User validated successfully')->with('user', $users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'valid' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        return redirect()->route('users.profile.index');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function showUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function showAdminDashboard(RankingController $rankingController)
    {
        $users = Auth::user();

        //dd($users);
        if ($users === null) {
            return back()->with('invalid', 'The user is invalid!');
        }

        $histories = Histories::all();
        $users = User::get();

        $rankingController = new RankingController();
        $ranking = $rankingController->getRankings();


        $history = new HistoryController();
        $totalScan = $history->TotalScan();
        $totalTimeIn = $history->TotalTimeIn();
        $totalTimeOut = $history->TotalTimeOut();
        $totalRegister = $history->TotalRegister();
        $dailyAttendance = $history->AllUserDailyAttendance();
        $recentlyAddedUser = $history->AllMonthlyUsers();

        return view('admin.dtr.dashboard', [
            'user' => $users,
            'totalScans' => $totalScan,
            'totalTimeIn' => $totalTimeIn,
            'totalTimeOut' => $totalTimeOut,
            'totalRegister' => $totalRegister,
            'array_daily' => $dailyAttendance,
            'histories' => $histories,
            'ranking' => $ranking,
            'recentlyAddedUser' => $recentlyAddedUser,
        ]);
    }

    public function showUserDashboard(RankingController $rankingController, DtrDownloadRequestController $dtrDownloadRequestController)
    {
        $users = Auth::user();

        if ($users === null) {
            return back()->with('invalid', 'The invalid user!');
        }

        if ($users->role === 'admin') {
            return back()->with('invalid', 'This user does not exists!');
        }

        try {
            //later if not admin
            //convert all the dateitme details
            $histories = $users->history()->latest()->get()->map(function ($history) {
                return [
                    'user' => $history->firstname . ' ' . $history->lastname,
                    'description' => $history->description,
                    'extra_description' => $history->extra_description,
                    'datetime' => Carbon::parse($history->datetime)->format('F j, Y'),
                    'timeFormat' => Carbon::parse($history->datetime)->format('g:i A'),
                ];
            })->toArray();

            $history = new HistoryController();
            $totalScan = $history->UserTotalScan();
            $totalTimeIn = $history->UserTotalTimeIn();
            $totalTimeOut = $history->UserTotalTimeOut();
            $totalRegister = $history->UserTotalRegister();
            $dailyAttendance = $history->AllUserDailyAttendance();

            //ranking controller
            $userRanking = $rankingController->getRankings();

            //get the download request list
            $downloadRequest = $dtrDownloadRequestController->UserdownloadRequestStatusDashboard();

            return view('users.dashboard', [
                'user' => $users,
                'userTimeStarted' => Carbon::parse($users->starting_date)->format('F j, Y'),
                'totalScan' => $totalScan,
                'totalTimeIn' => $totalTimeIn,
                'totalTimeOut' => $totalTimeOut,
                'totalRegister' => $totalRegister,
                'histories' => $histories,
                'array_daily' => $dailyAttendance,
                'downloadRequest' => $downloadRequest,
            ]);
        } catch (\Exception $ex) {
            return back()->with('invalid', 'Invalid user!');
        }
    }

    public function showAdminScanner()
    {
        //$users = User::all();

        //$histories = $users->history()->latest()->get();


    }

    public function showAdminHistory(RankingController $rankingController, HistoryController $historyController)
    {
        $histories = Histories::with('user')->get();
        $users = User::with('history')->get();

        //histories and users
        $records = [];

        foreach ($histories as $history) {
            $user = $users->firstWhere('id', $history->user_id);
            $records[] = [
                'user' => $user,
                'history' => $history,
            ];
        }

        $ranking = $rankingController->getRankings();
        $array_daily = $historyController->AllUserDailyAttendance();

        //dd($records);

        return view('admin.dtr.history.index', [
            'records' => $records,
            'ranking' => $ranking,
            'array_daily' => $array_daily,
        ]);
    }

    public function showAdminCreateHistory(RankingController $rankingController, HistoryController $historyController)
    {
        $histories = Histories::with('user')->get();
        $users = User::with('history')->get();

        //histories and users
        $records = [];

        foreach ($histories as $history) {
            $user = $users->firstWhere('id', $history->user_id);
            $records[] = [
                'user' => $user,
                'history' => $history,
            ];
        }

        return view('admin.dtr.history.create', [
            'records' => $records,
            'users' => $users,
        ]);
    }

    public function createAdminHistory(Request $request, RankingController $rankingController, HistoryController $historyController)
    {
        try {
            DB::beginTransaction();

            // Debugging - Check incoming data structure
            //echo "<pre>";
            //echo "Incoming Request Data:\n";
            print_r($request->all());

            // Validate request to ensure required fields exist
            $request->validate([
                'user_fullname' => 'required|array',
                'history_description' => 'required|array',
                'history_datetime' => 'required|array',
            ]);

            //echo "\nValidation Passed\n";

            $historyRecords = [];
            $extra_description = null;

            // Loop through `user_fullname` array
            $processedUsers = [];

            foreach ($request->user_fullname as $index => $userId) {
                if (!isset($request->history_description[$userId]) || !isset($request->history_datetime[$userId])) {
                    continue; // Skip users with missing history data
                }

                if (in_array($userId, $processedUsers)) {
                    continue; // Skip duplicate user
                }
                $processedUsers[] = $userId;

                foreach ($request->history_description[$userId] as $key => $desc) {
                    // Convert to lowercase
                    $desc = strtolower($desc);

                    // Determine extra description
                    $extra_description = Str::contains($desc, 'late') ? 'late' : null;

                    // Remove " | late" or any variation of "| late" with extra spaces
                    $desc = preg_replace('/\s*\|\s*late\s*/i', '', $desc);


                    //echo "Processing Entry [$key]: Description = '$desc', Extra = '$extra_description'\n";

                    $historyRecords[] = [
                        'user_id' => $userId,
                        'description' => trim($desc), // Trim to clean up any remaining spaces
                        'extra_description' => $extra_description,
                        'datetime' => $request->history_datetime[$userId][$key] ?? now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert records in bulk
            if (!empty($historyRecords)) {
                DB::table('histories')->insert($historyRecords);
                //echo "\nInserted " . count($historyRecords) . " records into history table.\n";
            } else {
                //echo "\nNo records to insert.\n";
            }

            DB::commit(); // Commit transaction if everything is successful

            //echo "</pre>";

            return back()->with('success', 'Admin history records saved successfully!');
        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaction if any error occurs

            return back()->with('error', 'Failed to save history records: ' . $e->getMessage());
        }
    }

    public function showAdminHistoryEdit($id, Request $request, RankingController $rankingController, HistoryController $historyController)
    {
        // Fetch the history record by ID
        $history = Histories::with('user')->findOrFail($id); // Automatically returns 404 if not found

        // Pass the data to a view
        return view('admin.edit-histories', [
            'history' => $history,
        ]);
    }

    public function editAdminHistory($id, Request $request, RankingController $rankingController, HistoryController $historyController)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Fetch the history record by ID
            $history = Histories::findOrFail($id);

            // Validate request data
            $validatedData = $request->validate([
                'history_description' => 'required|string|max:255',
                'history_datetime' => 'required|date',
            ]);

            // Default extra value
            $extra = null;

            // Check and modify history_description
            if ($validatedData['history_description'] === 'Time In | Late') {
                $cleanDescription = 'time in'; // Remove '| Late'
                $extra = 'late'; // Add extra flag
            } else {
                $cleanDescription = strtolower($validatedData['history_description']);
            }

            // Update history record
            $history->description = $cleanDescription;
            $history->extra_description = $extra; // Store extra if applicable
            $history->datetime = $validatedData['history_datetime'];
            $history->save();

            // Commit transaction
            DB::commit();

            return redirect()->route('admin.dtr.history')->with('success', 'History record updated successfully.');
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update history: ' . $e->getMessage());
        }
    }

    public function showAdminUsers(RankingController $rankingController, HistoryController $historyController)
    {
        $users = User::get();

        $ranking = $rankingController->getRankings();
        $array_daily = $historyController->AllUserDailyAttendance();

        return view('admin.dtr.interns.index', [
            'users' => $users,
            'ranking' => $ranking,
            'array_daily' => $array_daily,
        ]);

    }

    public function showUserDetails($id, DtrSummaryController $dtrSummaryController, DtrDownloadRequestController $dtrDownloadRequestController)
    {
        $user = User::find($id);

        $downloadRequest = $dtrDownloadRequestController->UserdownloadRequestPage();

        $histories = $user->history()->latest()->get()->map(function ($history) {
            return [
                'user' => $history->firstname . ' ' . $history->lastname,
                'description' => $history->description,
                'extra_description' => $history->extra_description,
                'datetime' => Carbon::parse($history->datetime)->format('F j, Y'),
                'timeFormat' => Carbon::parse($history->datetime)->format('g:i A'),
            ];
        })->toArray();

        // //convert $user to request
        // $request = [
        // //convert $user to request
        // $request = [
        //     'id' => $user->id,
        // ];

        // //convert $request to request object
        // $request = new Request($request);

        // //get the yearly totals
        // $yearlyTotals = $dtrSummaryController->showAdminUserDtrSummary($request);

        return view('admin.dtr.interns.show', [
            'user' => $user,
            'histories' => $histories,
            'downloadRequest' => $downloadRequest,
            //'yearlyTotals' => $yearlyTotals,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, FileController $fileController)
    {
        try {
            DB::beginTransaction();


            $data = $request->validate([
                'file' => 'nullable|image|max:5120',
            ]);

            $user = User::find($request->user_id);
            if (!$user) {
                return back()->with('invalid', 'The input is invalid. Please try again!');
            }

            $image_url = null;
            $image_description = null;


            if ($request->hasFile('file')) {
                $file = $request['file'];

                // Fetch the profile record
                $profile = Profile::where('id', $user->profile_id)->first();
                if (!$profile) {
                    return response()->json(['error' => 'Profile not found'], 404);
                }

                // Fetch the associated file record
                $file_id = $profile->file_id;
                $fileRecord = File::find($file_id);
                if (!$fileRecord) {
                    return response()->json(['error' => 'File not found'], 404);
                }

                try {
                    // Process the file (ensure your fileController->edit() method handles this correctly)
                    $fileFormat = $fileController->edit($request, $fileRecord->description);
                    $image_url = $fileFormat->original['data']['preview_url'] ?? null;
                    $image_description = $fileFormat->original['data']['id'] ?? null;
                } catch (\Exception $e) {
                    // Log the error or handle it gracefully
                    return response()->json(['error' => 'File processing failed: ' . $e->getMessage()], 500);
                }

                // Update the file record with new data
                $fileRecord->update([
                    'description' => $image_description,
                    'path' => $image_url,
                ]);
            }

            //@dd($request->all(), Profile::find($user->profile_id)?->files->path);

            // $user->update($request->only([
            //     'firstname', 'lastname', 'middlename', 'email', 'phone', 'gender',
            //     'address', 'student_no', 'emergency_contact_fullname',
            //     'emergency_contact_number', 'emergency_contact_address'
            // ]));

            $updateData = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'middlename' => $request['middlename'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'gender' => $request['gender'],
                'address' => $request['address'],
                'student_no' => $request['student_no'],
                'emergency_contact_number' => $request['emergency_contact_number'],
                'emergency_contact_fullname' => $request['emergency_contact_fullname'],
                'emergency_contact_address' => $request['emergency_contact_address'],
                'status' => $request['status'] ?? 'active',
            ];

            // Only update starting_date if it exists in the request
            if (!empty($request['starting_date'])) {
                $updateData['starting_date'] = $request['starting_date'];
            }

            // Only update expiry_date if it exists in the request
            if (!empty($request['expiry_date'])) {
                $updateData['expiry_date'] = $request['expiry_date'];
            }

            $user->update($updateData);


            if (!empty($request['school'])) {
                $school = School::where('description', $request['school'])->first();

                if ($school) {
                    $user->update([
                        'school' => $school->description,
                        'school_id' => $school->id,
                    ]);
                } else {
                    return back()->withErrors(['school' => 'Selected school does not exist.']);
                }
            }


            $user->save();

            DB::commit();
            return redirect()->back()->with('update', 'Updated Successfully! The uploaded image will take a minute to render.');
            //return back()->with('update', 'Updated Successfully!')->with(['image_url' => $image_url]);
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('invalid', $ex->getMessage());
        }
    }

    public function adminUpdate(Request $request, FileController $fileController)
    {
        try {

            $data = $request->validate([
                'file' => 'nullable|image|max:5120',
                'password' => 'nullable|string|confirmed|min:8',
            ]);

            DB::beginTransaction();

            if ($request['type'] === 'removeProfile') {
                $user = Auth::user();

                // Ensure user has a profile before accessing file_id
                $fileRecord = File::find(optional($user->profiles)->file_id);

                // Default profile images based on gender
                $profile_image = ($user->gender === 'male')
                    ? 'https://lh3.googleusercontent.com/d/15xbsTPp-MWc48TbxAaZ20wisUWwtQioq'
                    : 'https://lh3.googleusercontent.com/d/1FU9OpkgA-FTk3RrUnpoY_n5c9F6eQ4lA';

                if (!$fileRecord) {
                    return response()->json(['error' => 'File record not found'], 404);
                }

                try {
                    // Download the file from the link
                    $response = Http::get($profile_image);

                    if ($response->failed()) {
                        return response()->json(['error' => 'Failed to download file', 'details' => $response->body()], 500);
                    }

                    // Save the file temporarily
                    $tempFilePath = tempnam(sys_get_temp_dir(), 'profile_');
                    file_put_contents($tempFilePath, $response->body());

                    // Convert to Laravel UploadedFile
                    $file = new \Illuminate\Http\UploadedFile(
                        $tempFilePath,
                        'profile.jpg',
                        'image/jpeg',
                        null,
                        true
                    );

                    // Create a new empty request
                    $request = new Request();

                    // Manually set the uploaded file
                    $request->files->set('file', $file);

                    // Call the update function directly with the file
                    //$fileFormat = app(FileController::class)->update(new Request(['file' => $file]), $fileRecord->description);
                    $fileFormat = $fileController->edit($request, $fileRecord->description);
                    $image_url = $fileFormat->original['data']['preview_url'] ?? null;
                    $image_description = $fileFormat->original['data']['id'] ?? null;
                } catch (\Exception $e) {
                    return response()->json(['error' => 'File processing failed: ' . $e->getMessage()], 500);
                }

                // Update the file record
                $fileRecord->update([
                    'description' => $image_description,
                    'path' => $image_url,
                ]);

                return back()->with('success', 'Profile reset to default successfully.');
            }

            $user = User::find(Auth::user()->id);
            if (!$user) {
                return back()->with('invalid', 'The input is invalid. Please try again!');
            }

            $image_url = null;
            $image_description = null;


            if ($request->hasFile('file')) {
                $file = $request['file'];

                // Fetch the profile record
                $profile = Profile::where('id', $user->profile_id)->first();
                if (!$profile) {
                    return response()->json(['error' => 'Profile not found'], 404);
                }

                // Fetch the associated file record
                $file_id = $profile->file_id;
                $fileRecord = File::find($file_id);
                if (!$fileRecord) {
                    return response()->json(['error' => 'File not found'], 404);
                }

                try {
                    // Process the file (ensure your fileController->edit() method handles this correctly)
                    $fileFormat = $fileController->edit($request, $fileRecord->description);
                    $image_url = $fileFormat->original['data']['preview_url'] ?? null;
                    $image_description = $fileFormat->original['data']['id'] ?? null;
                } catch (\Exception $e) {
                    // Log the error or handle it gracefully
                    return response()->json(['error' => 'File processing failed: ' . $e->getMessage()], 500);
                }

                // Update the file record with new data
                $fileRecord->update([
                    'description' => $image_description,
                    'path' => $image_url,
                ]);
            }

            $updateData = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'middlename' => $request['middlename'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'gender' => $request['gender'],
                'address' => $request['address'],
            ];

            // Only update the password if the user provided a new one
            if (!empty($request['password'])) {
                $updateData['password'] = Hash::make($request['password']);
            }

            // @dd($request->all(), $updateData);

            $user->update($updateData);


            DB::commit();
            return redirect()->back()->with('update', 'Updated Successfully! The uploaded image will take a minute to render.');
            //return back()->with('update', 'Updated Successfully!')->with(['image_url' => $image_url]);
        } catch (\Exception $ex) {
            // @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('invalid', $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.profile.index');
    }

    public function showDashboard()
    {
        if (Auth::user()->role === 'admin') {
            return $this->showAdminDashboard(new RankingController());
        }
        return $this->showUserDashboard(new RankingController());
    }

    public function showRequest(DtrDownloadRequestController $dtrDownloadRequestController)
    {

        //get the dtr request list
        $downloadRequest = $dtrDownloadRequestController->UserdownloadRequestPage();

        return view('users.request.index', ['downloadRequest' => $downloadRequest]);
    }

    public function showAdminApprovals()
    {
        $dtrDownloadRequest = DtrDownloadRequest::with('users')->get();

        $dtrDownloadRequest = collect($dtrDownloadRequest)->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->users->firstname . ' ' . substr($user->users->middlename, 0, 1) . '. ' . $user->users->lastname,
                'title' => 'Request for DTR Approval',
                'month' => $user->month,
                'year' => $user->year,
                'user_id' => $user->user_id,
                'status' => $user->status,
                'date_requested' => Carbon::parse($user->created_at)->format('M d, Y'),
            ];
        })->sortByDesc('date_requested');

        return view('admin.dtr.approvals.index', [
            'approvals' => $dtrDownloadRequest,
        ]);
    }

    public function showEditUsers($id)
    {
        $user = User::where('id', $id)->first();

        return view('admin.dtr.interns.edit', [
            'user' => $user,
        ]);
    }
}