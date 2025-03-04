<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
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

        return view('pages.graphic_designer.joborder.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.graphic_designer.joborder.show', compact('job_draft'));
    }

    public function create($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('pages.graphic_designer.joborder.create', compact('job_draft'));
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

        return redirect()->route('graphic.approve')->with('Status', 'Draft Created Successfully');
    }

    public function edit($id)
    {
        // Fetch the job draft with related models
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'parentDraft')->find($id);

        // Pass both the job draft and the latest job draft to the view
        return view('pages.graphic_designer.joborder.edit', compact('job_draft'));
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

        return redirect()->route('graphic.approve')->with('Status', 'Draft Updated Successfully');
    }

    public function accept($id)
    {
        if (!auth()->user()->signature) {
            return redirect()->route('graphic.approve')->with('Status', 'No Signature Found');
        }
        $job_draft = JobDraft::find($id);

        $job_draft->update([
            'status' => 'pending',
            'date_started' => Carbon::now(),
            'date_target' => Carbon::now()->addDays($job_draft->days_to_add),
            'signature_worker' => auth()->user()->signature,
            'worker_signed' => auth()->user()->id
        ]);

        return redirect()->route('graphic.approve')->with('Status', 'Job Order Accepted Successfully');
    }
}
