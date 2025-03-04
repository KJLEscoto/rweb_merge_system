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

        return view('pages.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $revisions = Revision::with('jobDraft')->where('job_draft_id', $id)->get();
        return view('pages.revision.show', compact('revisions'));
    }

    public function edit($id)
    {
        $revisions = Revision::with(['jobDraft.jobOrder', 'jobDraft.client'])->where('job_draft_id', $id)->where('status', 'pending')->first();
        return view('pages.revision.edit', compact('revisions'));
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
            'status' => 'Submitted to Operations',
            'draft' => $request->draft
        ]);
        return redirect()->route('revision', compact('job_draft'));
    }
}
