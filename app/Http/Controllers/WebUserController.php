<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Page;
use App\Models\Privilege;
use App\Models\Profile;
use App\Models\Role;
use App\Models\RoleChannel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        $pages = Page::all();
        $privilege = Privilege::all();

        return view('admin.web-development.users.index', compact('pages', 'privilege', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $pages = Page::all();
        $privileges = Privilege::all();
        return view('admin.web-development.users.create', compact('users', 'pages', 'privileges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeGdrive(Request $request, FileController $filecontroller)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'role_id' => ['required'],
                'phone' => ['required', 'string', 'max:20'],
                'pages' => ['required', 'array'],
                'privileges' => ['required', 'array'],
                'address' => ['required', 'string', 'max:500'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|file|max:2048',
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

            $user = new User();

            //store the value
            $user->name = $data['name'];
            $user->firstname = $data['name'];
            $user->middlename = $data['name'];
            $user->lastname = $data['name'];
            $user->gender = 'Male';
            $user->role = Role::where('id', $data['role_id'])->first()->position;
            $user->status = 'active';
            $user->role_id = $data['role_id'];
            $user->address = $data['address'];
            $user->password = Hash::make($data['password']);
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->student_no = rand();
            $user->emergency_contact_number = Str::random(6);
            $user->emergency_contact_fullname = Str::random(6);
            $user->emergency_contact_address = Str::random(6);
            $user->profile_id = $profile->id;

            $user->save();
            $user->fresh();

            foreach ($data['pages'] as $page) {
                foreach ($request->privileges as $index => $privilege) {
                    if ($page === $index) {
                        foreach ($privilege as $priv) {
                            RoleChannel::create([
                                'user_id' => $user->id,
                                'page_id' => Page::where('description', 'like', '%' . $page . '%')->first()->id,
                                'privilege_id' => Privilege::where('description', 'like', '%' . $priv . '%')->first()->id,
                            ]);
                        }
                    }
                }
            }

            $user = User::all();
            $pages = Page::all();
            $privilege = Privilege::all();

            DB::commit();

            return view('admin.web-development.users.index', compact('pages', 'privilege', 'user'));
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }

    public function store(Request $request, FileController $filecontroller)
    {
        try {

            DB::beginTransaction();

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'role_id' => ['required'],
                'phone' => ['required', 'string', 'max:20'],
                'pages' => ['required', 'array'],
                'privileges' => ['required', 'array'],
                'address' => ['required', 'string', 'max:500'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|file|max:2048',
            ]);

            $file_id = null;

            if ($request->hasFile('image')) {
                $fileResponse = $filecontroller->store(new Request(['file' => $request->file('image')]));

                if ($fileResponse->getStatusCode() === 201) {
                    $file_id = $fileResponse->getData()->file->id;
                } else {
                    DB::rollBack();
                    return back()->with('Status', 'Failed to store uploaded image.');
                }
            } else {
                if ($request->has('image_url')) {
                    $fileResponse = $filecontroller->store(new Request(['image_url' => $request->input('image_url')]));
                } else {
                    // Default local fallback image path
                    $defaultImagePath = '/resources/img/default_female.png'; // Ensure this image exists
                    $fileResponse = $filecontroller->store(new Request(['image_url' => $defaultImagePath]));
                }

                if ($fileResponse->getStatusCode() === 200) {
                    $file_id = $fileResponse->getData()->file->id;
                } else {
                    DB::rollBack();
                    return back()->with('Status', 'Failed to store local fallback image.');
                }
            }

            $profile = Profile::create([
                'description' => 'User ' . $request->name,
                'file_id' => $file_id,
            ]);

            $user = new User();

            // Store the value
            $user->name = $data['name'];
            $user->firstname = $data['name'];
            $user->middlename = $data['name'];
            $user->lastname = $data['name'];
            $user->gender = 'Male';
            $user->role = Role::where('id', $data['role_id'])->first()->position;
            $user->status = 'active';
            $user->role_id = $data['role_id'];
            $user->address = $data['address'];
            $user->password = Hash::make($data['password']);
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->student_no = rand();
            $user->emergency_contact_number = Str::random(6);
            $user->emergency_contact_fullname = Str::random(6);
            $user->emergency_contact_address = Str::random(6);
            $user->profile_id = $profile->id;

            $user->save();
            $user->fresh();

            foreach ($data['pages'] as $page) {
                foreach ($request->privileges as $index => $privilege) {
                    if ($page === $index) {
                        foreach ($privilege as $priv) {
                            RoleChannel::create([
                                'user_id' => $user->id,
                                'page_id' => Page::where('description', 'like', '%' . $page . '%')->first()->id,
                                'privilege_id' => Privilege::where('description', 'like', '%' . $priv . '%')->first()->id,
                            ]);
                        }
                    }
                }
            }

            $users = User::all();
            $pages = Page::all();
            $privileges = Privilege::all();

            DB::commit();

            return view('admin.web-development.users.index', compact('pages', 'privileges', 'user'));
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();

        return view('admin.web-development.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $pages = Page::get();
        $privileges = Privilege::get();

        //Pages 
        //Privileges

        //@dd('Pages: ', $user->RoleChannelPages(), 'Privileges ', $user->RoleChannelPrivileges(), 'stop the car');

        $myPages = $user->RoleChannelPages();
        $myPrivileges = $user->RolechannelPrivileges();
        $myRoleChannels = $user->role_channels;

        //@dd(Page::get(), Privilege::get(), $myPrivileges, $myPages);

        return view('admin.web-development.users.edit', compact('user', 'pages', 'privileges', 'myPrivileges', 'myPages', 'myRoleChannels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FileController $fileController)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'role_id' => ['required'],
                'phone' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|file|max:2048',
                'image_url' => 'nullable|string',
            ]);

            $user = User::findOrFail($id);
            $profile = Profile::findOrFail($user->profile_id);
            $file_id = $profile->file_id;

            if ($request->hasFile('image')) {
                $fileResponse = $fileController->store(new Request(['file' => $request->file('image')]));

                if ($fileResponse->getStatusCode() === 201) {
                    $file_id = $fileResponse->getData()->file->id;
                } else {
                    DB::rollBack();
                    return back()->with('Status', 'Failed to store uploaded image.');
                }
            } elseif ($request->has('image_url')) {
                $fileResponse = $fileController->store(new Request(['image_url' => $request->input('image_url')]));

                if ($fileResponse->getStatusCode() === 201) {
                    $file_id = $fileResponse->getData()->file->id;
                } else {
                    DB::rollBack();
                    return back()->with('Status', 'Failed to store local fallback image.');
                }
            } elseif (!$file_id) { //if there is no file at all, use default.
                $defaultImagePath = '/resources/img/default_female.png';
                $fileResponse = $fileController->store(new Request(['image_url' => $defaultImagePath]));
                if ($fileResponse->getStatusCode() === 201) {
                    $file_id = $fileResponse->getData()->file->id;
                } else {
                    DB::rollBack();
                    return back()->with('Status', 'Failed to store default image.');
                }
            }

            $profile->update(['file_id' => $file_id]);

            $user->update([
                'name' => $data['name'],
                'firstname' => $data['name'],
                'middlename' => $data['name'],
                'lastname' => $data['name'],
                'role_id' => $data['role_id'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);

            // Handle RoleChannels only if 'pages' and 'privileges' are present
            if ($request->has('pages') && $request->has('privileges')) {
                // Delete existing RoleChannels for the user
                RoleChannel::where('user_id', $user->id)->delete();

                // Add new RoleChannels based on the updated request data
                foreach ($request->pages as $page) {
                    if (isset($request->privileges[$page])) { // Check if privileges exist for this page
                        foreach ($request->privileges[$page] as $priv) {
                            RoleChannel::create([
                                'user_id' => $user->id,
                                'page_id' => Page::where('description', 'like', '%' . $page . '%')->first()->id,
                                'privilege_id' => Privilege::where('description', 'like', '%' . $priv . '%')->first()->id,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.web.users')->with('success', 'User updated successfully.');
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
