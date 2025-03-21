<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Privilege;
use App\Models\Role;
use App\Models\RoleChannel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    public function store(Request $request)
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

            DB::commit();
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
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
    public function edit($id)
    {
        $user = User::where('id', $id)->first();

        return view('admin.web-development.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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