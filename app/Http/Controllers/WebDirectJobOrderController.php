<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WebJobOrder;
use App\Models\WebProject;
use App\Models\WebProjectChannel;
use Illuminate\Http\Request;

class WebDirectJobOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_projects = WebProject::with(['web_project_channels', 'issuer', 'client'])->get();
        return view('admin.web-development.direct-job-order.index', compact('web_projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = User::all();
        return view('admin.web-development.direct-job-order.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'clients' => 'required|array',
            'clients.web_designer' => 'required|array',
            'clients.front_end' => 'required|array',
            'clients.back_end' => 'required|array',
            'client_id' => 'required|integer', // Assuming client_id exists in clients table
            'date_started' => 'required|date|before_or_equal:date_target',
            'date_target' => 'required|date|after_or_equal:date_started',
            'instructions' => 'required|string',
        ]);

        // Create the web job order
        $web_job_order = WebJobOrder::create([
            'status' => 'oten ni kent'
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
            'web_designer' => $request->clients['web_designer'],
            'front_end' => $request->clients['front_end'],
            'back_end' => $request->clients['back_end']
        ];

        // Loop through each role and create a WebProjectChannel for each user in that role
        $firstIteration = true; // Flag to ensure date_started is saved only once

        foreach ($roles as $role => $users) {
            foreach ($users as $user_id) {
                WebProjectChannel::create([
                    'user_id' => $user_id,
                    'web_job_order_id' => $web_job_order->id,
                    'project_id' => $web_project->id,
                    'type' => $role,
                    'status' => 'pending',
                    'date_started' => $firstIteration ? $request->date_started : null, // Save only once
                    'date_targeted' => $firstIteration ? $request->date_target : null,
                ]);

                $firstIteration = false; // After the first save, set to false
            }
        }


        return redirect()->route('admin.web.direct-job-order')->with('success', 'Job Order Created Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $web_project_channel = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')->find($id);
        return view('admin.web-development.direct-job-order.channels.show', compact('web_project_channel'));
    }

    public function showProjectChannels($id)
    {
        $web_project = WebProject::find($id);
        $web_project_channels = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')->where('web_job_order_id', $id)->get();
        return view('admin.web-development.direct-job-order.channels.index', compact('web_project_channels', 'web_project'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = User::all();
        $web_project = WebProject::with('web_project_channels', 'client')->find($id);
        return view('admin.web-development.direct-job-order.channels.edit', compact('web_project', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {}

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
