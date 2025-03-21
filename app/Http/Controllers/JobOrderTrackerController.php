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
        // Validate request before proceeding
        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'content_writer_id' => 'sometimes|integer',
            'graphic_designer_id' => 'sometimes|integer|nullable',
            'client_id' => 'sometimes|integer|nullable',
            'date_target' => 'sometimes|date',
            'date_started' => 'sometimes|date',
            // 'days_to_add' => 'sometimes|integer'
        ]);

        // Find the job draft by ID
        $job_draft = JobDraft::findOrFail($id);

        // Build update array only for fields that are filled
        $updateDraft = [];
        if ($request->filled('date_started')) {
            $updateDraft['date_started'] = $request->date_started;
        }
        if ($request->filled('date_target')) {
            $updateDraft['date_target'] = $request->date_target;
        }
        // if ($request->filled('days_to_add')) {
        //     $updateDraft['days_to_add'] = $request->days_to_add;
        // }
        if ($request->filled('graphic_designer_id')) {
            $updateDraft['graphic_designer_id'] = $request->graphic_designer_id;
        }
        if ($request->filled('client_id')) {
            $updateDraft['client_id'] = $request->client_id;
        }
        if ($request->filled('content_writer_id')) {
            $updateDraft['content_writer_id'] = $request->content_writer_id;
        }

        // Update only if there's something to update
        if (!empty($updateDraft)) {
            $job_draft->update($updateDraft);
        }

        // Build update array for JobOrder fields conditionally
        $jobOrderUpdate = [];
        if ($request->filled('title')) {
            $jobOrderUpdate['title'] = $request->title;
        }
        if ($request->filled('description')) {
            $jobOrderUpdate['description'] = $request->description;
        }
        if (!empty($jobOrderUpdate)) {
            $job_order = JobOrder::findOrFail($job_draft->job_order_id);
            $job_order->update($jobOrderUpdate);
        }

        return redirect()->route('admin.smm.track.index')
            ->with('Status', 'Job Order Updated Successfully');
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

    public function destroyDraft($id)
    {
        $job_draft = JobDraft::findOrFail($id);

        // Delete related job drafts first to avoid foreign key constraint issues
        $job_draft->delete();

        // Redirect back to the previous page with a status message
        return back()->with('Status', 'Job Order Deleted');
    }
}
