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

        if ($job_draft->type == "content_writer") {
            JobDraft::create([
                'job_order_id' => $job_draft->job_order_id,
                'type' => 'graphic_designer',
                'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                'date_target' => Carbon::now()->addDays(3)->toDateString(),
                'status' => 'Waiting for Graphic Designer Approval',
                'content_writer_id' => $job_draft->content_writer_id,
                'graphic_designer_id' => $job_draft->graphic_designer_id,
                'client_id' => $job_draft->client_id,
                'reference_draft_id' => $id,
                'signature_supervisor' => $job_draft->signature_supervisor,
                'supervisor_signed' => $job_draft->supervisor_signed
            ]);
        } elseif ($job_draft->type == "graphic_designer") {
            // Update Database with Signature Path
            $job_draft->update([
                'status' => 'Submitted to Client',
            ]);
        }


        return redirect()->route('topmanager.approve')->with('Status', 'Job Order Approved Successfully');
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
        return redirect()->route('topmanager.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
