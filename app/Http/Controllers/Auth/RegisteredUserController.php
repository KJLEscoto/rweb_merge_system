<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

            //top management role
            $top_management_role = [
                'top_manager',
                'supervisor',
                'operations',
            ];

            //employee role
            $employee_role = [
                'content_writer',
                'client',
                'graphic_designer',
                'accounting',
            ];

            if (in_array($user->role, $top_management_role)) {
                // Assign role-specific permissions

            } else if (in_array($user->role, $employee_role)) {
                // Assign role-specific permissions
            }

            @dd($user);

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

    public function update(Request $request, $id)
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
        ]);

        $picturePath = $user->image; // Preserve existing image if not updating

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');
            $file->move($destination, $file_name);
            $picturePath = 'uploads/' . $file_name;
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