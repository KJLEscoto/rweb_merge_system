<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TopApprovalController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::where('status', 'Submitted to Top Manager')
            ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
            ->get();

        return view('admin.smm.topmanager.joborderapproval.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.topmanager.joborderapproval.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.topmanager.joborderapproval.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {

        $job_draft = JobDraft::findOrFail($id);

        // Update Database with Signature Path
        $job_draft->update([
            'status' => 'Submitted to Client',
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.approved.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->draft,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Approved Successfully');
    }

    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.topmanager.joborderapproval.declineform', compact('job_draft'));
    }

    public function decline(Request $request, $id)
    {
        $request->validate([
            'summary' => 'required',
        ]);

        $job_draft = JobDraft::find($id);

        // Update Database with Signature Path
        Revision::create([
            'job_draft_id' => $id,
            'declined_by' => auth()->user()->id,
            'summary' => $request->summary,
            'last_draft' => $job_draft->draft,
            'revision_date' => Carbon::now()->toDateString(), // Set date_started to today
            'status' => 'pending'
        ]);

        $job_draft->update([
            'status' => 'Revision',
            'draft_op_sign' => null,
            'op_signed_draft' => null,
            'draft_sup_sign' => null,
            'sup_signed_draft' => null
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.rejected.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $request->summary,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
