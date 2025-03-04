<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Http\Request;

class JobOrderController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner')->get();
        return view('pages/admin/joborder/joborder', compact('job_drafts'));
    }
    public function create()
    {
        $content_writers = User::where('role_id', 3)->get();
        $graphic_designers = User::where('role_id', 4)->get();
        $clients = User::where('role_id', 1)->get();

        return view('pages.admin.joborder.create', compact('content_writers', 'graphic_designers', 'clients'));
    }
    public function store(Request $request)
    {
        // Validate request before proceeding
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'content_writer_id' => 'required|integer',
            'graphic_designer_id' => 'required|integer',
            'client_id' => 'required|integer',
            'date_target' => 'required|date',
            'date_started' => 'required|date'
        ]);

        $job_order = JobOrder::create([
            'title' => $request->title,
            'description' => $request->description,
            'issued_by' => auth()->user()->id,
        ]);

        $job_draft = JobDraft::create([
            'job_order_id' => $job_order->id,
            'type' => 'content_writer',
            'date_started' => $request->date_started,
            'date_target' => $request->date_target,
            'status' => 'pending',
            'content_writer_id' => $request->content_writer_id,
            'graphic_designer_id' => $request->graphic_designer_id,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('joborder')->with('Status', 'Job Order Create Successfully');
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.admin.joborder.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $clients = User::with('role')->where('role_id', 1)->get();
        $graphic_designers = User::with('role')->whereNotIn('role_id', [1, 3, 5])->get();
        $content_writers = User::with('role')->whereNotIn('role_id', [1, 4, 5])->get();

        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('pages.admin.joborder.edit', compact('job_draft', 'content_writers', 'graphic_designers', 'clients'));
    }

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
            'days_to_add' => 'sometimes|integer'
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
        if ($request->filled('days_to_add')) {
            $updateDraft['days_to_add'] = $request->days_to_add;
        }
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

        return redirect()->route('joborder')
            ->with('Status', 'Job Order Updated Successfully');
    }
}
