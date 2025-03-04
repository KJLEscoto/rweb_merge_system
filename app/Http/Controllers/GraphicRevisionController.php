<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Illuminate\Http\Request;

class GraphicRevisionController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
            ->where('status', 'Revision')
            ->where('type', 'graphic_designer')
            ->where('graphic_designer_id', auth()->user()->id) // Cleaner way to get the authenticated user's ID
            ->get(); // Retrieve all records

        return view('pages.graphic_designer.revision.index', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('pages.graphic_designer.revision.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions')->find($id);
        return view('pages.graphic_designer.revision.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'draft' => 'required',
        ]);
        $job_draft = JobDraft::find($id);
        $job_draft->update([
            'status' => 'Submitted to Operation',
            'draft' => $request->draft
        ]);

        return redirect()->route('graphic.revisions')->with('Status', 'Job Order Updated Successfully');
    }
}
