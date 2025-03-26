<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WebJobOrder;
use App\Models\WebProject;
use App\Models\WebProjectChannel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();

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

            DB::commit();

            //sent back to the 
            $web_projects = WebProject::with(['web_project_channels', 'issuer', 'client'])->get();
            return view('admin.web-development.direct-job-order.index', compact('web_projects'));
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollback();
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
        $web_project_channel = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')->find($id);
        return view('admin.web-development.direct-job-order.channels.show', compact('web_project_channel'));
    }

    public function showProjectChannels($id)
    {
        $web_project = WebProject::find($id);
        $web_project_channels = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')->where('project_id', $id)->get();
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

    public function editProjectChannels(Request $request, $id)
    {
        // @dd('stop', $request->all(), $id); // Remove or comment out after debugging
        $users = $request->input('users');
        $newUsers = $request->input('newUsers'); // Get the new users array

        DB::beginTransaction(); // Start a database transaction

        try {

            // Handle deleted users
            $existingChannelIds = WebProjectChannel::where('project_id', $id)->pluck('id')->toArray();
            $updatedChannelIds = $users ? array_keys($users) : [];
            $deletedChannelIds = array_diff($existingChannelIds, $updatedChannelIds);

            if (!empty($deletedChannelIds)) {
                WebProjectChannel::whereIn('id', $deletedChannelIds)->delete();
            }

            // Update WebProject table
            WebProject::where('id', $id)->update([
                'title' => $request->input('title'),
                'client_id' => $request->input('client_id'),
                'instructions' => $request->input('instructions'),
            ]);

            // Update WebProjectChannel table for existing users
            if ($users) {
                foreach ($users as $channelId => $types) {
                    foreach ($types as $type => $userId) {
                        // @dd($types, $users, $channelId); // Remove or comment out after debugging
                        WebProjectChannel::where('id', $channelId)
                            ->update(['user_id' => $userId]);
                    }
                }
            }

            // Create new WebProjectChannel records for new users
            if ($newUsers) {
                foreach ($newUsers as $newUser) {
                    if (isset($newUser['type']) && isset($newUser['user'])) {

                        $web_job_order = WebJobOrder::create([
                            'status' => 'Job order for ' . User::where('id', $newUser['user'])->first()->name,
                        ]);

                        $web_project_channel = WebProjectChannel::create([
                            'project_id' => $id,
                            'web_job_order_id' => $web_job_order->id,
                            'type' => $newUser['type'],
                            'user_id' => $newUser['user'],
                            'date_started' => $newUser['type'] == 'web_designer' ? $request->input('date_started') : null,
                            'date_targeted' => $newUser['type'] == 'web_designer' ? $request->input('date_target') : null,
                            'status' => 'pending',
                            'sub_status' => $newUser['type'] == 'web_designer' ? 'Site Map' : null,
                        ]);
                    }
                }
            }

            //Update web_project_channels table with the dates.
            WebProjectChannel::where('project_id', $id)
                ->update([
                    'date_started' => $request->input('date_started'),
                    'date_targeted' => $request->input('date_target')
                ]);

            DB::commit(); // Commit the transaction if all updates succeed
        } catch (\Exception $e) {
            @dd($e->getMessage());
            DB::rollback(); // Rollback the transaction if an error occurs
            // Handle the exception (e.g., log the error, display an error message)
            return back()->withErrors(['error' => 'An error occurred while updating the project. Please try again.']);
        }

        return redirect()->route('admin.web.direct-job-order.showProjectChannels', $id)->with('success', 'Project updated successfully.');
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
        $web_project = WebProject::find($id);
        $web_project->delete();
        $web_projects = WebProject::with(['web_project_channels', 'issuer', 'client'])->get();

        return redirect()->route('admin.web.direct-job-order')->with('success', 'Project deleted successfully.');
    }
}
