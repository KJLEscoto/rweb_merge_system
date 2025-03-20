<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupervisorTaskController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where(function ($query) use ($authuser) {
            $query->where('content_writer_id', $authuser->id)
                ->orWhere('graphic_designer_id', $authuser->id);
        })
            ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client']) // Ensures relations are loaded
            ->get();

        return view('admin.smm.supervisor.task.list', compact('job_drafts'));
    }

    public function show($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('admin.smm.supervisor.task.show', compact('job_draft'));
    }

    public function create($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('admin.smm.supervisor.task.create', compact('job_draft'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required'
        ]);

        $job_draft = JobDraft::findOrFail($id);

        $job_draft->update([
            'draft' => $request->draft,
            'status' => 'Submitted to Operations',
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.task.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $request->draft,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.supervisor.task')->with('Status', 'Draft Created Successfully');
    }

    public function edit($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('admin.smm.supervisor.task.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required'
        ]);

        $job_draft = JobDraft::findOrFail($id);

        $job_draft->update([
            'draft' => $request->draft,
            'status' => 'Submitted to Operations',
        ]);

        return redirect()->route('admin.smmsupervisor.task')->with('Status', 'Draft Updated Successfully');
    }

    public function accept($id)
    {
        if (!auth()->user()->signature) {
            return redirect()->route('admin.smm.supervisor.task')->with('Status', 'No Signature Found');
        }
        $job_draft = JobDraft::find($id);

        $job_draft->update([
            'status' => 'pending',
            // 'date_started' => Carbon::now(),
            // 'date_target' => Carbon::now()->addDays($job_draft->days_to_add),
            'signature_worker' => auth()->user()->signature,
            'worker_signed' => auth()->user()->id
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.accept.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->jobOrder->description,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.supervisor.task')->with('Status', 'Job Order Accepted Successfully');
    }
}
