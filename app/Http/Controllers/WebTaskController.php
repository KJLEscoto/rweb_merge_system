<?php

namespace App\Http\Controllers;

use App\Models\WebProject;
use App\Models\WebProjectChannel;
use Illuminate\Http\Request;

class WebTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_project_channels = WebProjectChannel::with('web_project')->where('user_id', auth()->user()->id)->whereNot('date_started', null)->whereIn('status', ['pending', 'accept'])->get();
        return view('admin.web-development.task.index', compact('web_project_channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $web_project_channel = WebProjectChannel::with('web_project', 'web_job_orders', 'web_project.client', 'web_project.issuer', 'users', 'web_project.supervisor')->find($id);
        return view('admin.web-development.task.create', compact('web_project_channel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required'
        ]);

        $web_project_channel = WebProjectChannel::find($id);

        $web_project_channel->update([
            'draft' => $request->instructions,
            'status' => 'Submitted to Operation'
        ]);

        return redirect()->route('admin.web.task')->with('success', 'Project Submitted Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function accept($id)
    {
        $web_project_channel = WebProjectChannel::find($id);

        $web_project_channel->update([
            'status' => 'accepted'
        ]);
        return redirect()->back()->with('success', 'Project accepted successfully!');
    }
}
