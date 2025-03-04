<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Illuminate\Http\Request;

class JobOrderTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authuser = auth()->user();

        if ($authuser->role_id == "1") {
            $job_drafts = JobDraft::where('client_id', $authuser->id)
                ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->orderby('date_started', 'desc')
                ->get();
        } elseif ($authuser->role_id == "2" || $authuser->role_id == "5" || $authuser->role_id == "6") {
            $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->orderBy('date_started', 'desc')
                ->get();
        } elseif ($authuser->role_id == "3") {
            $job_drafts = JobDraft::where('content_writer_id', $authuser->id)
                ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->where('type', 'content_writer')
                ->orderBy('date_started', 'desc')
                ->get();
        } elseif ($authuser->role_id == "4") {
            $job_drafts = JobDraft::where('graphic_designer_id', $authuser->id)
                ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->where('type', 'graphic_designer')
                ->orderBy('date_started', 'desc')
                ->get();
        }

        return view('pages.track.index', compact('job_drafts'));
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
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.track.show', compact('job_draft'));
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
