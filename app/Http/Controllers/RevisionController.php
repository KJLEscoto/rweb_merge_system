<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevisionController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Initialize an empty collection
        $job_drafts = collect();

        if (in_array($authuser->role_id, [2, 5, 6])) {
            // Fetch job orders that have at least one revision in their drafts for both content and graphic
            $job_drafts_content = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->whereHas('revisions')
                ->where('type', 'content_writer')
                ->where('content_writer_id', $authuser->id)
                ->get();

            $job_drafts_graphic = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->whereHas('revisions')
                ->where('type', 'graphic_designer')
                ->where('graphic_designer_id', $authuser->id)
                ->get();

            // Merge both collections into one
            $job_drafts = $job_drafts_content->merge($job_drafts_graphic);
        } elseif ($authuser->role_id == 3) {
            $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->whereHas('revisions')
                ->where('type', 'content_writer')
                ->where('content_writer_id', $authuser->id)
                ->get();
        } elseif ($authuser->role_id == 4) {
            $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                ->whereHas('revisions')
                ->where('type', 'graphic_designer')
                ->where('graphic_designer_id', $authuser->id)
                ->get();
        }

        return view('admin.smm.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $revisions = Revision::with('jobDraft')->where('job_draft_id', $id)->get();
        return view('admin.smm.revision.show', compact('revisions'));
    }

    public function edit($id)
    {
        $revisions = Revision::with(['jobDraft.jobOrder', 'jobDraft.client'])->where('job_draft_id', $id)->where('status', 'pending')->first();
        return view('admin.smm.revision.edit', compact('revisions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required',
        ]);
        $job_draft = JobDraft::find($id);
        $revision = Revision::where('job_draft_id', $id)->where('status', 'pending')->first();
        $revision->update([
            'submitted_draft' => $request->draft,
            'date_submitted' => Carbon::now()->toDateString(), // Set date_started to today,
            'status' => 'complete'
        ]);
        $job_draft->update([
            'status' => 'Submitted to Assistant Supervisor',
            'draft' => $request->draft
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.revise.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->jobOrder->description,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.revision', compact('job_draft'));
    }
}
