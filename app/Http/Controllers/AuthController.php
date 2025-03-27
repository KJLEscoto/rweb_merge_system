<?php

namespace App\Http\Controllers;

use App\Models\Histories;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function registerGdrive(Request $request, FileController $fileController)
    {
        try {

            DB::beginTransaction();

            $data = $request->validate([
                'firstname' => 'required|string|max:255',
                'middlename' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'school' => 'required|string|max:255',
                'student_no' => 'required|string|max:255',

                'emergency_contact_fullname' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:255',

                'password' => 'required|string|min:8|confirmed',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
            ]);

            // Generate QR Code
            $qr_code = 'QR_' . Str::random(10) . '_' . Str::random(10);

            $profile_image = ($data['gender'] === 'male')
                ? 'https://lh3.googleusercontent.com/d/15xbsTPp-MWc48TbxAaZ20wisUWwtQioq' // Image in the public/images folder
                : 'https://lh3.googleusercontent.com/d/1FU9OpkgA-FTk3RrUnpoY_n5c9F6eQ4lA'; // Image in the public/images folder

            $request->merge([
                'image_url' => $profile_image,
            ]);

            //send the image link to the controller
            $file_records = $fileController->store($request);

            $file_id = $file_records->original['file']->id;

            $profile_record = Profile::create([
                'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
                'file_id' => $file_id,
            ]);

            $user = User::create([
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'school' => \App\Models\School::where('id', $data['school'] + 1)->first()->description,
                'student_no' => $data['student_no'],
                'emergency_contact_fullname' => $data['emergency_contact_fullname'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'emergency_contact_address' => $data['emergency_contact_address'],
                'qr_code' => $qr_code,
                'profile_id' => $profile_record->id, // Save external image URL or uploaded image URL
                'school_id' => $request->school + 1,
                'expiry_date' => Carbon::now()->addMonths(3),
                'role_id' => Role::where('position', 'like', 'user')->first()->id,
                'role' => 'user',
                'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
            ]);



            DB::commit();

            return redirect()->route('show.login')->with('success', 'Congratulations! You are now registered!');
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('invalid', $ex->getMessage());
        }
    }

    public function register(Request $request, FileController $fileController)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'firstname' => 'required|string|max:255',
                'middlename' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'school' => 'required|string|max:255',
                'student_no' => 'required|string|max:255',
                'emergency_contact_fullname' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
            ]);

            // Generate QR Code
            $qr_code = 'QR_' . Str::random(10) . '_' . Str::random(10);

            // Determine default profile image based on gender
            $profile_image = '/resources/img/default_' . ($data['gender'] === 'male' ? 'male' : 'female') . '.png';

            // Store the image using FileController even if it's local
            $file_records = null;
            if ($request->hasFile('profile_image')) {
                $file_records = $fileController->store(new Request(['file' => $request->file('profile_image')]));
                if ($file_records->getStatusCode() === 200) {
                    $profile_image = $file_records->getData()->file->path;
                } else {
                    throw new \Exception('FileController failed to store profile image: ' . $file_records->getContent());
                }
            } else {
                $file_records = $fileController->store(new Request(['image_url' => asset($profile_image)]));
                if ($file_records->getStatusCode() === 200) {
                    $profile_image = $file_records->getData()->file->path;
                } else {
                    throw new \Exception('FileController failed to store default profile image: ' . $file_records->getContent());
                }
            }

            $file_id = $file_records->getData()->file->id;

            $profile_record = Profile::create([
                'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
                'file_id' => $file_id, // Store file_id from FileController
            ]);

            $user = User::create([
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'school' => \App\Models\School::where('id', $data['school'] + 1)->first()->description,
                'student_no' => $data['student_no'],
                'emergency_contact_fullname' => $data['emergency_contact_fullname'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'emergency_contact_address' => $data['emergency_contact_address'],
                'qr_code' => $qr_code,
                'profile_id' => $profile_record->id,
                'school_id' => $request->school + 1,
                'expiry_date' => Carbon::now()->addMonths(3),
                'role_id' => Role::where('position', 'like', 'user')->first()->id,
                'role' => 'user',
                'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
                'image' => $profile_image, // Store relative path from file controller
            ]);

            DB::commit();

            return redirect()->route('show.login')->with('success', 'Congratulations! You are now registered!');
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('invalid', $ex->getMessage());
        }
    }

    public function adminRegisterGdrive(Request $request, FileController $fileController)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'school' => 'nullable|string|max:255',
            'student_no' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'role_id' => 'required',

            'emergency_contact_fullname' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',

            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        // Generate QR Code
        $qr_code = 'QR_' . Str::random(10) . '_' . Str::random(10);

        // Check if a file is uploaded
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imagePath = $image->store('profile_images', 'public'); // Store in storage/app/public/profile_images
            $profile_image = asset('storage/' . $imagePath); // Convert to accessible URL
        } else {
            // Use external image links based on gender
            $profile_image = ($data['gender'] === 'male')
                ? 'https://lh3.googleusercontent.com/d/15xbsTPp-MWc48TbxAaZ20wisUWwtQioq' // Image in the public/images folder
                : 'https://lh3.googleusercontent.com/d/1FU9OpkgA-FTk3RrUnpoY_n5c9F6eQ4lA'; // Image in the public/images folder
        }

        $request->merge([
            'image_url' => $profile_image,
        ]);

        //send the image link to the controller
        $file_records = $fileController->store($request);

        $file_id = $file_records->original['file']->id;

        $profile_record = Profile::create([
            'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
            'file_id' => $file_id,
        ]);

        $user = User::create([
            'role' => $data['role'],
            'role_id' => $data['role_id'],
            'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'student_no' => $data['student_no'],
            'emergency_contact_fullname' => $data['emergency_contact_fullname'],
            'emergency_contact_number' => $data['emergency_contact_number'],
            'emergency_contact_address' => $data['emergency_contact_address'],
            'qr_code' => $qr_code,
            'profile_id' => $profile_record->id, // Save external image URL or uploaded image URL
        ]);

        return redirect()->route('show.login')->with('success', 'Congratulations! You are now registered!');
    }

    public function adminRegister(Request $request, FileController $fileController)
    {
        echo "adminRegister function started.\n";

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'school' => 'nullable|string|max:255',
            'student_no' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'role_id' => 'required',
            'emergency_contact_fullname' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:255',
            'emergency_contact_address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
        ]);

        echo "Data validated.\n";

        // Generate QR Code
        $qr_code = 'QR_' . Str::random(10) . '_' . Str::random(10);

        echo "QR code generated: " . $qr_code . "\n";

        // Determine profile image path
        $profile_image = '/resources/img/default_' . ($data['gender'] === 'male' ? 'male' : 'female') . '.png';

        echo "Default profile image path: " . $profile_image . "\n";

        // Store the image using FileController even if it's local
        $file_records = null;
        if ($request->hasFile('profile_image')) {
            echo "Profile image file found.\n";
            $file_records = $fileController->store(new Request(['file' => $request->file('profile_image')]));
            echo "FileController store called with file.\n";
            $profile_image = $file_records->getData()->file->path;
            echo "Profile image path from FileController: " . $profile_image . "\n";
        } else {
            // Handle the case where the default image file does not exist
            echo "Default image file not found.\n";
            // You might want to set a fallback or return an error response here
            // For example, you could store a placeholder image or use a default URL
            $file_records = $fileController->store(new Request(['image_url' => '/resources/img/default_female.png']));
            $profile_image = $file_records->getData()->file->path;
        }

        echo "File records retrieved.\n";

        $file_id = $file_records->getData()->file->id;

        echo "File ID retrieved: " . $file_id . "\n";

        $profile_record = Profile::create([
            'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
            'file_id' => $file_id, // Store file_id from FileController
        ]);

        echo "Profile record created.\n";

        $user = User::create([
            'role' => $data['role'],
            'role_id' => $data['role_id'],
            'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'student_no' => $data['student_no'],
            'emergency_contact_fullname' => $data['emergency_contact_fullname'],
            'emergency_contact_number' => $data['emergency_contact_number'],
            'emergency_contact_address' => $data['emergency_contact_address'],
            'qr_code' => $qr_code,
            'profile_id' => $profile_record->id,
            'image' => $profile_image, // Store relative path
        ]);

        echo "User record created.\n";

        echo "adminRegister function completed.\n";

        return redirect()->route('show.login')->with('success', 'Congratulations! You are now registered!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, HistoryController $historyController, UserController $userController)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'type' => 'required|string|in:admin,user', // Ensure type is either 'admin' or 'user'
        ]);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($data['type'] === 'admin') {
                return $this->adminLogin($request, $user, $userController);
            } else {
                return $this->userLogin($request, $user);
            }
        }

        throw ValidationException::withMessages([
            'invalid' => 'Invalid login credentials.',
        ]);
    }

    private function adminLogin(Request $request, $user, UserController $userController)
    {

        $admin_roles = ['assistant_supervisor', 'operations_supervisor', 'top_management', 'admin'];
        if (
            !in_array($user->roles->position, $admin_roles)
        ) {
            Auth::logout();
            return back()->with('invalid', 'This user does not exist.');
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

        return redirect()->route('admin.smm.dashboard')->with([
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

    private function userLogin(Request $request, $user)
    {
        if (!is_null($user->expiry_date) && Carbon::parse($user->expiry_date)->lessThanOrEqualTo(Carbon::now())) {
            $user->status = 'inactive';
            $user->save();
            Auth::logout();

            return back()->with('invalid', 'The account is either expired or inactive. Please contact the administrator for more information.');
        }

        $timezone = 'Asia/Manila';

        $isStarted = !is_null($user->starting_date) &&
            Carbon::parse($user->starting_date)->timezone($timezone)->startOfDay()->lessThanOrEqualTo(Carbon::now($timezone)->startOfDay());

        $isExpired = !is_null($user->expiry_date) &&
            Carbon::parse($user->expiry_date)->timezone($timezone)->lessThanOrEqualTo(Carbon::now($timezone));

        $isActive = $user->status === 'active';

        if ($isStarted && $isActive && !$isExpired) {
            return redirect()->route('users.dashboard');
        }

        Auth::logout();
        // return response()->json(['error' => 'You are not allowed to log in. Please contact the admin for more information'], 403);

        if (
            !is_null($user->starting_date)
            && !(Carbon::parse($user->starting_date)->timezone($timezone)->startOfDay()->lessThanOrEqualTo(Carbon::now($timezone)->startOfDay()))
        ) {

            Auth::logout();
            return redirect()->route('show.login')->with('invalid', "You are forced to log out. This account will be open at " .
                Carbon::parse($user->starting_date)->timezone($timezone)->format('M j, Y') .
                ". Please contact admin for more information.");
        }

        return back()->with('invalid', 'This account is not started yet. Please contact admin for more information.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user->role == 'admin') {
            Auth::logout();
            return redirect()->route('show.admin.login');
        } else {
            Auth::logout();
            return redirect()->route('landing.page');
        }
    }

    public function showResetPassword(Request $request)
    {
        $token = $request->query('token');

        // Ensure token is provided
        if (!$token) {
            return redirect()->route('show.login')->with('invalid', 'Invalid reset link!');
        }

        // Retrieve the reset record by filtering with email
        $reset_password = DB::table('password_reset_tokens')->whereNotNull('email')->first();

        if (!$reset_password || !Hash::check($token, $reset_password->token)) {
            return redirect()->route('show.login')->with('invalid', 'This link is expired or invalid!');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request, EmailController $emailController)
    {
        //email verification
        $data = $request->validate([
            'email' => 'required|string|email',
        ]);
        //dd($data);

        //validation here
        $user = User::where('email', $request->email)->first();


        //get the class of the Auth
        $authenticatedUser = Auth::class;

        if (!$user) {
            return back()->with('invalid', 'Email not found');
        }

        if ($authenticatedUser::check()) {
            if ($user->email != $authenticatedUser::user()->email) {
                return back()->with('invalid', 'Email does not matched!');
            }
        }

        //send the reset password link to the email
        $resetPassword = $emailController->EmailResetPassword($user);

        //return the success response
        return back()->with('success', 'We\'ve sent a link to reset your password.');
    }

    public function showAdminUsersCreate()
    {
        return view('admin.dtr.interns.create');
    }

    public function showAdminUsersCreatePostGdrive(Request $request, FileController $fileController)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'firstname' => 'required|string|max:255',
                'middlename' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'school' => 'required|string|max:255',
                'student_no' => 'required|string|max:255',

                'emergency_contact_fullname' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:255',

                'password' => 'required|string|min:8|confirmed',
            ]);

            $profile_image = ($data['gender'] === 'male')

                ? 'https://lh3.googleusercontent.com/d/15xbsTPp-MWc48TbxAaZ20wisUWwtQioq' // Image in the public/images folder
                : 'https://lh3.googleusercontent.com/d/1FU9OpkgA-FTk3RrUnpoY_n5c9F6eQ4lA'; // Image in the public/images folder

            $request->merge([
                'image_url' => $profile_image,
            ]);

            //send the image link to the controller
            $file_records = $fileController->store($request);

            $file_id = $file_records->original['file']->id;

            $profile_record = Profile::create([
                'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
                'file_id' => $file_id,
            ]);

            //return response()->json(['message' => $request->all()],Response::HTTP_INTERNAL_SERVER_ERROR);

            //dd($data);
            //Generate QR Code
            $qr_code = 'QR' . '_' . Str::random(10) . '_' . Str::random(10);

            $school_id = null;
            if ($data['school'] != null) {
                $school_id = \App\Models\School::where('description', $data['school'])->first()->id;
            }

            //dd($qr_code);
            $user = User::create(
                [
                    'firstname' => $data['firstname'],
                    'middlename' => $data['middlename'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'address' => $data['address'],
                    'school' => \App\Models\School::where('description', $data['school'])->first()->description,
                    'student_no' => $data['student_no'],
                    'emergency_contact_fullname' => $data['emergency_contact_fullname'],
                    'emergency_contact_number' => $data['emergency_contact_number'],
                    'emergency_contact_address' => $data['emergency_contact_address'],
                    'qr_code' => $qr_code,
                    'expiry_date' => Carbon::now()->addMonths(3),
                    'school_id' => $school_id,
                    'profile_id' => $profile_record->id, // Save external image URL or uploaded image URL
                    'role' => 'user',
                    'role_id' => Role::where('position', 'user')->first()->id,
                    'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
                ]
            );

            DB::commit();

            return back()->with([
                'success' => 'Account Created Successfully!',
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            @dd($ex->getMessage());
        }
    }

    public function showAdminUsersCreatePost(Request $request, FileController $fileController)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'firstname' => 'required|string|max:255',
                'middlename' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'school' => 'required|string|max:255',
                'student_no' => 'required|string|max:255',
                'emergency_contact_fullname' => 'required|string|max:255',
                'emergency_contact_number' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120', // Add validation for profile image
            ]);

            if ($request->hasFile('profile_image')) {
                $file_records = $fileController->store($request);
                if ($file_records->getStatusCode() !== 200) {
                    DB::rollBack();
                    return back()->with('invalid', $file_records->getData()->message ?? 'Image upload failed');
                }
                $file_id = $file_records->getData()->file->id;
            } else {
                // Default profile image based on gender
                $profile_image = ($data['gender'] === 'male')
                    ? '/resources/img/default_male.png'
                    : '/resources/img/default_female.png';

                $defaultFile = \App\Models\File::create([
                    'description' => 'Default Profile Image',
                    'path' => $profile_image,
                    'type' => 'image/png',
                    'size' => 0, // Set size to 0 or appropriate default
                ]);

                $file_id = $defaultFile->id;
            }

            $profile_record = Profile::create([
                'description' => 'User ' . $data['lastname'] . ' ' . substr($data['firstname'], 0, 1) . '. \'s profile',
                'file_id' => $file_id,
            ]);

            $qr_code = 'QR' . '_' . Str::random(10) . '_' . Str::random(10);

            $school_id = null;
            if ($data['school'] != null) {
                $school_id = \App\Models\School::where('description', $data['school'])->first()->id;
            }

            $user = User::create([
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'school' => \App\Models\School::where('description', $data['school'])->first()->description,
                'student_no' => $data['student_no'],
                'emergency_contact_fullname' => $data['emergency_contact_fullname'],
                'emergency_contact_number' => $data['emergency_contact_number'],
                'emergency_contact_address' => $data['emergency_contact_address'],
                'qr_code' => $qr_code,
                'expiry_date' => Carbon::now()->addMonths(3),
                'school_id' => $school_id,
                'profile_id' => $profile_record->id,
                'role' => 'user',
                'role_id' => Role::where('position', 'user')->first()->id,
                'name' => $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'],
            ]);

            DB::commit();

            return back()->with(['success' => 'Account Created Successfully!']);
        } catch (\Exception $ex) {
            DB::rollBack();
            @dd($ex->getMessage());
        }
    }
}
