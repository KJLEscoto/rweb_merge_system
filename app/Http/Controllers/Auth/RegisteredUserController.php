<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Models\File;
use App\Models\Page;
use App\Models\Privilege;
use App\Models\Profile;
use App\Models\Role;
use App\Models\RoleChannel;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    public function index()
    {
        $authuser = auth()->user();
        $users = User::all();


        return view('admin.smm.users.users', ['users' => $users]);
    }

    public function create(): View
    {
        return view('admin.smm.users.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, FileController $filecontroller): RedirectResponse
    {

        try {
            DB::beginTransaction();

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role_id' => ['required'],
                'phone' => ['required'],
                'address' => ['required'],
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|file|max:2048',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $picturePath = null;
            $file_records = null;
            $file_id = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file_records = $filecontroller->store(new Request(['file' => $request['image']]));

                //proceed to change the file attributes
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $destination = public_path('uploads');
                $file->move($destination, $file_name);
                $picturePath = 'uploads/' . $file_name;  // Assign to picturePath

                $file_id = $file_records->original['file']->id;
            } else {
                $profile_image = 'https://lh3.googleusercontent.com/d/1x1vyLdfoXxUjCTmab_5fGSDXU_zVJ3RI'; // Image in the public/images folder
                $file_records = $filecontroller->store(new Request(['image_url' => $profile_image]));
                $file_id = $file_records->original['file']->id;
            }


            $profile = Profile::create([
                'description' => 'User' . $request->name,
                'file_id' => $file_id,
            ]);

            $user = User::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $picturePath, // Save the image path
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->name,
                'middlename' => $request->name,
                'lastname' => $request->name,
                'student_no' => rand(),
                'emergency_contact_number' => rand(),
                'emergency_contact_fullname' => str::random(),
                'emergency_contact_address' => rand(),
                'profile_id' => $profile->id,
                'role' => Role::where('id', $request->role_id)->first()->position,
            ]);

            // Define role groups
            $top_management_role = [
                'top_management',
                'operations_supervisor',
                'assistant_supervisor',
            ];

            $employee_role = [
                'content_writer',
                'client',
                'graphic_designer',
                'accounting',
            ];

            // Define accessible pages per role
            $role_pages = [
                'top_management' => Page::all(),
                'operations_supervisor' => Page::all(),
                'assistant_supervisor' => [
                    'dashboard',
                    'task',
                    'revision',
                    'approvals',
                    'track',
                    'users',
                    'instructions_manual',
                    'incoming_requests',
                    'profile',
                    'downloadables',
                    'users',
                ],
                'employee' => [
                    'dashboard',
                    'task',
                    'revision',
                    'approvals',
                    'track',
                    'profile',
                ],
            ];

            // Initialize pages and privileges
            $pages = null;
            $privileges = null;

            // Assign pages and privileges based on role
            if (isset($role_pages[$user->role])) {
                if (is_array($role_pages[$user->role])) {
                    // If the role has specific pages
                    $pages = Page::whereIn('description', $role_pages[$user->role])->get();
                    $privileges = Privilege::all();
                } else {
                    // If the role gets all pages (Top Managements & supervisors)
                    $pages = Page::all();
                    $privileges = Privilege::all();
                }
            } elseif (in_array($user->role, $employee_role)) {
                // Default employee pages
                $pages = Page::whereIn('description', $role_pages['employee'])->get();
                $privileges = Privilege::all();
            }

            // Assign role-specific permissions
            if ($pages && $privileges) {
                foreach ($pages as $page) {
                    foreach ($privileges as $privilege) {
                        RoleChannel::create([
                            'user_id' => $user->id,
                            'page_id' => $page->id,
                            'privilege_id' => $privilege->id,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.smm.users')->with('Status', 'Users Created Successfully');
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollback();
            return redirect()->route('admin.smm.users')->with('status', $ex->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch user or return 404 if not found
        return view('admin.smm.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->find($id);
        return view('admin.smm.users.edit', compact('user'));
    }

    public function update(Request $request, $id, FileController $filecontroller)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'role_id' => ['sometimes'],
            'phone' => ['sometimes'],
            'address' => ['sometimes'],
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id) // Ignore the current user's email
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'type' => 'nullable|string',
        ]);

        $picturePath = $user->image; // Preserve existing image if not updating
        $file_id = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_records = $filecontroller->edit(new Request(['file' => $request['image']]), File::find(User::find($id)->profiles->id)->description);

            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');
            $file->move($destination, $file_name);
            $picturePath = 'uploads/' . $file_name;
        } else if ($request->type == 'removeProfile') {
            $profile_image = 'https://lh3.googleusercontent.com/d/1x1vyLdfoXxUjCTmab_5fGSDXU_zVJ3RI'; // Image in the public/images folder
            $response = Http::get($profile_image);
            if ($response->failed()) {
                return back()->with('Status', 'Failed to download file: ' . $response->body());
            }

            $tempFilePath = tempnam(sys_get_temp_dir(), 'profile_');
            file_put_contents($tempFilePath, $response->body());

            $file = new \Illuminate\Http\UploadedFile(
                $tempFilePath,
                'profile.jpg',
                'image/jpeg',
                null,
                true,
            );

            // $request = new Request();

            // $request->files->set('file', $file);

            $file_records = $filecontroller->edit(new Request(['file' => $file]), File::find(User::find($id)->profiles->id)->description);
        }

        // Prepare the update array
        $updateData = [
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'role_id' => $request->role_id ?? $user->role_id,
            'address' => $request->address ?? $user->address,
            'image' => $picturePath,
            'email' => $request->email ?? $user->email,
        ];

        // Only update the password if a new one is provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($updateData);

        return redirect()->route('admin.smm.users')->with('Status', 'User Updated Successfully');
    }
}
