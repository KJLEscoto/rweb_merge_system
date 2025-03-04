<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    public function index()
    {
        $authuser = auth()->user();
        $users = User::all();


        return view('pages.users.users', ['users' => $users]);
    }

    public function create(): View
    {
        return view('pages.users.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role_id' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $picturePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads');
            $file->move($destination, $file_name);
            $picturePath = 'uploads/' . $file_name;  // Assign to picturePath
        }

        $user = User::create([
            'name' => $request->name,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $picturePath, // Save the image path
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users')->with('Status', 'Users Created Successfully');
    }

    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch user or return 404 if not found
        return view('pages.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('role')->find($id);
        return view('pages.users.edit', compact('user'));
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

        return redirect()->route('users')->with('Status', 'User Updated Successfully');
    }
}
