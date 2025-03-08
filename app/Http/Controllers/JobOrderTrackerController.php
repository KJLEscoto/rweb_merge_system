<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\User;
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
            $job_orders = JobOrder::whereHas('jobDrafts', function ($query) use ($authuser) {
                $query->where('client_id', $authuser->id);
            })
                ->with('latestJobDraft') // Fetch only one latest JobDraft
                ->orderBy('date_started', 'desc')
                ->get();
        } elseif ($authuser->role_id == "2" || $authuser->role_id == "5" || $authuser->role_id == "6") {
            $job_orders = JobOrder::with('latestJobDraft') // Fetch only one latest JobDraft
                ->get();
        } elseif ($authuser->role_id == "3") {
            // Get job orders where the latest job draft belongs to the content writer
            $job_orders = JobOrder::whereHas('latestJobDraft', function ($query) use ($authuser) {
                $query->where('content_writer_id', $authuser->id);
            })
                ->with(['latestJobDraft.contentWriter', 'latestJobDraft.graphicDesigner', 'latestJobDraft.client'])
                ->orderByDesc('id') // Order by latest job orders
                ->get();
        } elseif ($authuser->role_id == "4") {
            // Get job orders where the latest job draft belongs to the graphic designer
            $job_orders = JobOrder::whereHas('latestJobDraft', function ($query) use ($authuser) {
                $query->where('graphic_designer_id', $authuser->id);
            })
                ->with(['latestJobDraft.contentWriter', 'latestJobDraft.graphicDesigner', 'latestJobDraft.client'])
                ->orderByDesc('id') // Order by latest job orders
                ->get();
        }


        return view('admin.smm.track.index', compact('job_orders'));
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
        $job_order = JobOrder::with('jobDrafts', 'latestJobDraft')->find($id);
        return view('admin.smm.track.show', compact('job_order'));
    }

    public function showDraft($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.track.show-draft', compact('job_draft'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphic_designers = User::with('roles')->whereNotIn('role_id', [1, 3, 5])->get();
        $content_writers = User::with('roles')->whereNotIn('role_id', [1, 4, 5])->get();

        $job_order = JobOrder::with('latestJobDraft')->find($id);

        return view('admin.smm.admin.joborder.edit', compact('job_draft', 'content_writers', 'graphic_designers', 'clients'));
    }

    public function editDraft($id)
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphic_designers = User::with('roles')->whereNotIn('role_id', [1, 3, 5])->get();
        $content_writers = User::with('roles')->whereNotIn('role_id', [1, 4, 5])->get();

        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('admin.smm.track.edit-draft', compact('job_draft', 'content_writers', 'graphic_designers', 'clients'));
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
        dd('hello');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job_order = JobOrder::findOrFail($id);
        // Delete related job drafts first to avoid foreign key constraint issues
        $job_order->jobDrafts()->delete();

        // Delete the job Order form
        $job_order->delete();

        return redirect()->route('admin.smm.track.index')->with('Status', 'Job Order Deleted');
    }
}
