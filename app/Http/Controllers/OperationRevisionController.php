<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class OperationRevisionController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
            ->whereHas('revisions')
            ->where(function ($query) {
                $query->where('content_writer_id', auth()->user()->id)
                    ->orWhere('graphic_designer_id', auth()->user()->id);
            })
            ->get(); // Retrieve all records

        return view('admin.smm.admin.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('admin.smm.admin.revision.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('admin.smm.admin.revision.edit', compact('job_draft'));
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
        return redirect()->route('admin.smm.operation.revision', compact('job_draft'));
    }
}
