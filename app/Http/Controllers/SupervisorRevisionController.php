<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Illuminate\Http\Request;

class SupervisorRevisionController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
            ->where('status', 'Revision')
            ->where(function ($query) {
                $query->where('content_writer_id', auth()->user()->id)
                    ->orWhere('graphic_designer_id', auth()->user()->id);
            })
            ->get(); // Retrieve all records

        return view('pages.supervisor.revision.index', compact('job_drafts'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('pages.supervisor.revision.edit', compact('job_draft'));
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
        return redirect()->route('supervisor.revision', compact('job_draft'));
    }
}
