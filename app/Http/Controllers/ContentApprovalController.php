<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContentApprovalController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where('content_writer_id', $authuser->id)
            ->where('type', 'content_writer')
            ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->get();

        return view('pages.content_writer.joborder.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.content_writer.joborder.show', compact('job_draft'));
    }

    public function create($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.content_writer.joborder.create', compact('job_draft'));
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

        return redirect()->route('content.approve')->with('Status', 'Draft Created Successfully');
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.content_writer.joborder.edit', compact('job_draft'));
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

        return redirect()->route('content.approve')->with('Status', 'Draft Updated Successfully');
    }

    public function accept($id)
    {
        if (!auth()->user()->signature) {
            return redirect()->route('content.approve')->with('Status', 'No Signature Found');
        }

        $job_draft = JobDraft::find($id);

        $job_draft->update([
            'status' => 'pending',
            'date_started' => Carbon::now(),
            'date_target' => Carbon::now()->addDays($job_draft->days_to_add),
            'signature_worker' => auth()->user()->signature,
            'worker_signed' => auth()->user()->id
        ]);

        return redirect()->route('content.approve')->with('Status', 'Job Order Accepted Successfully');
    }
}
