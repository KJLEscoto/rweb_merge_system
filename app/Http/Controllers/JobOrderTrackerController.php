<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
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
        $job_order = JobOrder::with('jobDrafts')->find($id);
        return view('admin.smm.track.show', compact('job_order'));
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
    public function delete($id)
    {
        $job_order = JobOrder::findOrFail($id);
        // Delete related job drafts first to avoid foreign key constraint issues
        $job_order->jobDrafts()->delete();

        // Delete the job Order form
        $job_order->delete();

        return redirect()->route('admin.smm.track.index')->with('Status', 'Job Order Deleted');
    }
}
