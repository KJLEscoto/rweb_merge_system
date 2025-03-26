<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WebProject;
use App\Models\WebProjectChannel;
use Illuminate\Http\Request;

class WebTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_projects = WebProject::with('client')->get();
        return view('admin.web-development.track.index', compact('web_projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $web_project = WebProject::with('client', 'web_project_channels', 'issuer')->find($id);
        return view('admin.web-development.track.show', compact('web_project'));
    }

    public function showDraft() {}

    public function showEditDraft(Request $request, $project_id, $project_channel_id, $user_id)
    {
        $employees = User::get();
        $web_project_channel = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')
            ->where('id', $project_channel_id)
            ->where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->first();

        return view('admin.web-development.track.draft.edit', compact('web_project_channel', 'employees'));
    }

    public function editDraft(Request $request, $project_id, $project_channel_id, $user_id)
    {
        $employee = User::get();
        $web_project_channel = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')
            ->where('id', $project_channel_id)
            ->where('project_id', $project_id)
            ->get();

        return view('admin.web-development.track.draft.edit', compact('web_project_channel', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
