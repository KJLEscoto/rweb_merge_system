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

        return view('pages.topmanager.joborderapproval.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.topmanager.joborderapproval.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.topmanager.joborderapproval.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {

        $job_draft = JobDraft::findOrFail($id);

        // Update Database with Signature Path
        $job_draft->update([
            'status' => 'Submitted to Client',
        ]);

        return redirect()->route('topmanager.approve')->with('Status', 'Job Order Approved Successfully');
    }

    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.topmanager.joborderapproval.declineform', compact('job_draft'));
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
        return redirect()->route('topmanager.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
