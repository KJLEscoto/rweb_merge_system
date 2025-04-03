<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\WebProjectChannel;
use App\Models\WebRevisions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_project_channels = WebProjectChannel::with('web_project')->where('user_id', auth()->user()->id)->where('status', 'Revision')->get();
        return view('admin.web-development.revision.index', compact('web_project_channels'));
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
        try {
            DB::beginTransaction();
            $web_revision = new WebRevisions();
            $web_revision->update([
                'date_submitted' => Carbon::now(),
                'latest_draft' => $request->draft
            ]);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('error', 'Error while saving data. Please try again.');
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
        $web_revision = WebRevisions::with('web_project_channel', 'web_project_channel.web_project', 'web_project_channel.users', 'web_project_channel.web_project.client')->where('web_project_channel_id', $id)->first();
        return view('admin.web-development.revision.show', compact('web_revision'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $web_revision = WebRevisions::with('web_project_channel')->find($id);

        $web_revision->update([
            'date_submitted' => Carbon::now(),
            'latest_draft' => $request->draft
        ]);

        WebProjectChannel::find($web_revision->web_project_channel->id)->update([
            'status' => 'Submitted to Operations Supervisor',
            'draft' => $request->draft
        ]);

        return redirect()->route('admin.web.revision');
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
