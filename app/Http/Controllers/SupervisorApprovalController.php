<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupervisorApprovalController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::whereNotIn('status', ['pending', 'Submitted to Operations', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
            ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
            ->get();

        return view('admin.smm.supervisor.joborderapproval.list', compact('job_drafts'));
    }


    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.supervisor.joborderapproval.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.supervisor.joborderapproval.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'signature_supervisor'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_pad'    => 'nullable|string',
            'new_signature_pad' => 'nullable|string',
        ]);

        $signatureCount = 0;

        if ($request->hasFile('signature_supervisor')) {
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

        // Process the valid signature
        $job_draft = JobDraft::findOrFail($id);
        $imagePath = $job_draft->signature_supervisor; // Keep existing signature if no new one is uploaded

        if ($request->hasFile('signature_supervisor')) {
            $file = $request->file('signature_supervisor');
            $imagePath = 'signatures/signature_' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $imagePath);
        } elseif ($request->signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $imagePath = 'signatures/signature_' . time() . '.png';
            file_put_contents(public_path($imagePath), base64_decode($image));
        } elseif ($request->new_signature_pad) {
            $imagePath = auth()->user()->signature;
        }

        $job_draft->update([
            'draft_sup_sign' => $imagePath,
            'sup_signed_draft' => auth()->user()->id,
        ]);

        if ($job_draft->type == "content_writer") {
            if ($job_draft->works == "Content Only") {
                $job_draft->update([
                    'status' => 'Submitted to Top Manager',
                ]);
            } elseif ($job_draft->works == "Both") {

                $job_draft->update([
                    'status' => 'completed',
                ]);
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
                    'supervisor_signed' => $job_draft->supervisor_signed,
                    'works' => $job_draft->works
                ]);
            }
        } elseif ($job_draft->type == "graphic_designer") {
            // Update Database with Signature Path
            $job_draft->update([
                'status' => 'Submitted to Top Manager',
            ]);
        }

        return redirect()->route('admin.smm.supervisor.approve')->with('Status', 'Job Order Approved Successfully');
    }

    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.supervisor.joborderapproval.declineform', compact('job_draft'));
    }

    public function decline(Request $request, $id)
    {

        $request->validate([
            'summary' => 'required',
        ]);

        $job_draft = JobDraft::find($id);

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
        ]);
        return redirect()->route('admin.smm.supervisor.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
