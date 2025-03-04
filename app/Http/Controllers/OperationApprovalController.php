<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OperationApprovalController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::whereNotIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
            ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
            ->get();

        return view('pages.admin.joborderapproval.list', compact('job_drafts'));
    }


    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.admin.joborderapproval.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.admin.joborderapproval.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'signature_admin'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_pad'    => 'nullable|string',
            'new_signature_pad' => 'nullable|string',
        ]);

        $signatureCount = 0;

        if ($request->hasFile('signature_admin')) {
            $signatureCount++;
        }
        if (!empty($request->signature_pad)) {
            $signatureCount++;
        }
        if (!empty($request->new_signature_pad)) {
            $signatureCount++;
        }

        // If no signature was provided, return an error.
        if ($signatureCount === 0) {
            return redirect()->back()->withErrors(['signature' => 'A signature is required.'])->withInput();
        }

        // If more than one signature was provided, return an error.
        if ($signatureCount > 1) {
            return redirect()->back()->withErrors(['signature' => 'Only one signature is allowed.'])->withInput();
        }

        $job_draft = JobDraft::findOrFail($id);
        $imagePath = $job_draft->draft_op_sign; // Keep existing signature if no new one is uploaded

        if ($request->hasFile('signature_admin')) {
            $file = $request->file('signature_admin');
            $imagePath = 'signatures/signature_' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $imagePath);
        } elseif ($request->signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $imagePath = 'signatures/signature_' . time() . '.png';
            file_put_contents(public_path($imagePath), base64_decode($image));
        } elseif ($request->new_signature_pad) {
            $imagePath = auth()->user()->signature;
        }

        // Update Database with Signature Path
        $job_draft->update([
            'draft_op_sign' => $imagePath,
            'op_signed_draft' => auth()->user()->id,
            'status' => 'Submitted to Supervisor',
        ]);

        return redirect()->route('operation.approve')->with('Status', 'Job Order Approved Successfully');
    }

    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.admin.joborderapproval.declineform', compact('job_draft'));
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
        ]);
        return redirect()->route('operation.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
