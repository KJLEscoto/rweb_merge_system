<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\WebJobOrder;
use App\Models\WebProject;
use App\Models\WebProjectChannel;
use App\Models\WebRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_requests = WebRequest::with('issued_to', 'issued_by')->where('assigned_to', auth()->user()->id)->get();
        return view('admin.web-development.incoming-requests.index', compact('web_requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $web_request = WebRequest::find($id);

        if (!$web_request) {
            return back()->with('status', '404 Not Found'); // Corrected 'Status' to 'status' for consistency
        }

        $employee = User::all();
        $client_user_id = Role::where('position', 'like', '%client%')->first();

        if ($client_user_id) {
            // If you need to find users associated with the client role, use a relationship (if defined)
            // or join the users table. Assuming you have a relationship named 'users' in the Role model:

            $clients = $client_user_id->users; // Access users through the relationship
            //or if you need just the first one
            $client = $client_user_id->users()->first();

            // If you don't have a relationship, you'll need to join the tables.
            // Assuming your users table has a 'role_id' column:

            $clients = User::where('role_id', $client_user_id->id)->get();
            //or to get the first one
            $client = User::where('role_id', $client_user_id->id)->first();
        } else {
            $clients = null; // Or handle the case where no client role is found.
            //or
            $client = null;
        }

        // Example usage:
        if ($clients) {
            // If you have multiple operators, you can loop through them:
            if (is_iterable(value: $clients)) {
                foreach ($clients as $client) {
                    // Access operator properties like $operator->name, $operator->email, etc.
                    // ...
                }
            } else {
                //Access the single operator properties.
                //...
            }
        } else {
            // Handle the case where no operators were found.
            // ...
        }

        return view('admin.web-development.incoming-requests.create', compact('employee', 'clients', 'web_request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        try {
            DB::beginTransaction();

            $request->validate([
                'title' => 'required|string|max:255',
                'developers' => 'required|array',
                'developers.web_designer' => 'required|array',
                'developers.front_end' => 'required|array',
                'developers.back_end' => 'required|array',
                'client_id' => 'required|integer', // Assuming client_id exists in clients table
                'date_started' => 'required|date|before_or_equal:date_target',
                'date_target' => 'required|date|after_or_equal:date_started',
                'instructions' => 'required|string',
            ]);

            // Create the web project
            $web_project = WebProject::create([
                'title' => $request->title,
                'instructions' => $request->instructions,
                'issued_by_id' => auth()->user()->id,
                'supervisor_signed_id' => auth()->user()->id,
                'client_id' => $request->client_id
            ]);

            // Define the roles and their corresponding user IDs
            $roles = [
                'web_designer' => $request->developers['web_designer'],
                'front_end' => $request->developers['front_end'],
                'back_end' => $request->developers['back_end']
            ];

            // Loop through each role and create a WebProjectChannel for each user in that role
            $firstIteration = true; // Flag to ensure date_started is saved only once

            $debug = null;
            foreach ($roles as $role => $users) {
                foreach ($users as $user_id) {
                    $web_job_order = WebJobOrder::create([
                        'operation_signed_draft_id' => null,
                        'supervisor_signed_draft_id' => null,
                        'client_signed_id' => null,
                        'status' => 'Job order for ' . User::where('id', $user_id)->first()->name,
                    ]);
                    $web_project_channel = WebProjectChannel::create([
                        'user_id' => $user_id,
                        'web_job_order_id' => $web_job_order->id,
                        'project_id' => $web_project->id,
                        'type' => $role,
                        'status' => 'pending',
                        'date_started' => $role != 'web_designer' ? null : $request->date_started, // Save only once
                        'date_targeted' => $role != 'web_designer' ? null : $request->date_target,
                    ]);
                }
            }

            WebRequest::where('id', $id)->update([
                'status' => 'Submitted to Operations Supervisor'
            ]);

            DB::commit();

            //sent back to the 
            $web_projects = WebProject::with(['web_project_channels', 'issuer', 'client'])->get();
            $web_requests = WebRequest::with('issued_to', 'issued_by')->where('assigned_to', auth()->user()->id)->get();
            return view('admin.web-development.incoming-requests.index', compact('web_projects', 'web_requests '));
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //anyone can be able to assign operation job order if they have privileges and access to the page
        $web_request = WebRequest::with('issued_to', 'issued_by')->find($id);
        return view('admin.web-development.incoming-requests.show', compact('web_request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(WebRequest $webRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebRequest $webRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $webRequest = WebRequest::find($id);
            if (!$webRequest) {
                return back()->with('status', '404 Not Found'); // Corrected 'Status' to'status' for consistency
            }
            $webRequest->delete();
            DB::commit();
            return redirect()->route('admin.web.incoming-requests');
        } catch (\Exception $ex) {
            DB::rollback();
            return back()->with('status', 'Failed to delete request.');
        }
    }

    public function accept($id)
    {
        WebRequest::find($id)->update([
            'status' => 'accepted',
            'date_accepted' => Carbon::now(),
        ]);
        return redirect()->route('admin.web.incoming-requests');
    }
}
