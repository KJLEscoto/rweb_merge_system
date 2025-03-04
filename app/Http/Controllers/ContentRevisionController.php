<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Revision;
use Illuminate\Http\Request;

class ContentRevisionController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
            ->where('status', 'Revision')
            ->where('type', 'content_writer')
            ->where('content_writer_id', auth()->user()->id) // Cleaner way to get the authenticated user's ID
            ->get(); // Retrieve all records

        return view('pages.content_writer.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('pages.content_writer.revision.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('pages.content_writer.revision.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required',
        ]);
        $job_draft = JobDraft::find($id);
        $job_draft->update([
            'status' => 'Submitted to Operations',
            'draft' => $request->draft
        ]);
        return redirect()->route('content.revisions', compact('job_draft'));
    }
}
