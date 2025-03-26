<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GraphicApprovalController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where('graphic_designer_id', $authuser->id)
            ->where('type', 'graphic_designer')
            ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->get();

        return view('admin.smm.graphic_designer.joborder.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.graphic_designer.joborder.show', compact('job_draft'));
    }

    public function create($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('admin.smm.graphic_designer.joborder.create', compact('job_draft'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required'
        ]);

        $job_draft = JobDraft::findOrFail($id);

        $job_draft->update([
            'draft' => $request->draft,
            'status' => 'Submitted to Assistant Supervisor',
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

        return redirect()->route('admin.smm.graphic.approve')->with('Status', 'Draft Created Successfully');
    }

    public function edit($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('admin.smm.graphic_designer.joborder.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required'
        ]);

        $job_draft = JobDraft::findOrFail($id);

        $job_draft->update([
            'draft' => $request->draft,
            'status' => 'Submitted to Assistant Supervisor',
        ]);

        return redirect()->route('admin.smm.graphic.approve')->with('Status', 'Draft Updated Successfully');
    }

    public function accept($id)
    {
        if (!auth()->user()->signature) {
            return redirect()->route('admin.smm.graphic.approve')->with('Status', 'No Signature Found');
        }
        $job_draft = JobDraft::find($id);

        $job_draft->update([
            'status' => 'pending',
            'signature_worker' => auth()->user()->signature,
            'worker_signed' => auth()->user()->id
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => JobOrder::where('id', $job_draft->job_order_id)->first()->issued_by], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.accept.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->jobOrder->description,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.graphic.approve')->with('Status', 'Job Order Accepted Successfully');
    }
}
