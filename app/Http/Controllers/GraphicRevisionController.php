<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GraphicRevisionController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
            ->whereHas('revisions')
            ->where('type', 'graphic_designer')
            ->where('graphic_designer_id', auth()->user()->id) // Cleaner way to get the authenticated user's ID
            ->get(); // Retrieve all records

        return view('admin.smm.graphic_designer.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('admin.smm.graphic_designer.revision.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('admin.smm.graphic_designer.revision.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required',
        ]);
        $job_draft = JobDraft::find($id);
        $job_draft->update([
            'status' => 'Submitted to Assistant Supervisor',
            'draft' => $request->draft
        ]);

        $notificationController = new NotificationController();

        $issued_by = JobOrder::where('id', $job_draft->job_order_id)->first();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => $issued_by], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.revise.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->jobOrder->description,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('graphic.revisions')->with('Status', 'Job Order Updated Successfully');
    }
}
