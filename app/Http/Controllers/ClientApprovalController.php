<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientApprovalController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where('client_id', $authuser->id)
            ->where('status', 'Submitted to Client')
            ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->get();

        return view('admin.smm.client.joborder.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.client.joborder.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.client.joborder.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'signature_client'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'signature_pad'    => 'nullable|string',
            'new_signature_pad' => 'nullable|string',
            'summary' => 'required',
        ]);

        $signatureCount = 0;

        if ($request->hasFile('signature_client')) {
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
        $imagePath = null;
        if ($request->hasFile('signature_client')) {
            $file = $request->file('signature_client');
            $imagePath = 'signatures/signature_' . time() . '.' . $file->extension();
            $file->move(public_path('signatures'), $imagePath);
        } elseif ($request->signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->signature_pad);
            $imagePath = 'signatures/signature_' . time() . '.png';
            file_put_contents(public_path($imagePath), base64_decode($image));
        } elseif ($request->new_signature_pad) {
            $imagePath = auth()->user()->signature;
        }

        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        // Fetch the referenced content draft properly
        $content_draft = JobDraft::where('id', $job_draft->reference_draft_id)->first();

        $job_draft->update([
            'feedback' => $request->summary,
            'status' => 'completed',
            'date_completed' => now(),
            'client_signature' => $imagePath
        ]);

        $content_draft->update([
            'feedback' => $request->summary,
            'date_completed' => now(),
        ]);


        if ($job_draft->jobOrder->renewable == 1) {
            // return view('admin.smm.client.joborder.renew', compact('job_draft_id'));
            JobDraft::create([
                'job_order_id' => $job_draft->job_order_id,
                'type' => 'content_writer',
                'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                'date_target' => Carbon::now()->addDays(3)->toDateString(),
                'status' => 'Waiting for Content Writer Approval',
                'content_writer_id' => $job_draft->content_writer_id,
                'graphic_designer_id' => $job_draft->graphic_designer_id,
                'client_id' => $job_draft->client_id,
                'signature_supervisor' => $job_draft->signature_supervisor,
                'supervisor_signed' => $job_draft->supervisor_signed,
                'works' => $job_draft->works
            ]);
        }
        return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Approved Successfully');
    }
    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.client.joborderapproval.declineform', compact('job_draft'));
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
        return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Declined Successfully');
    }

    public function renew(Request $request, $id)
    {
        if ($request->renewable == 0) {
            return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Approved Successfully');
        } else {

            $job_draft = JobDraft::with('jobOrder')->find($id);

            if (!$job_draft) {
                return response()->json(['error' => 'Job draft not found'], 404);
            }

            if (!$job_draft->jobOrder) {
                return response()->json(['error' => 'Job order not found'], 404);
            }

            $job_order = JobOrder::find($job_draft->job_order_id);

            $job_order->update([
                'renewable' => $request->renewable,
            ]);

            JobDraft::create([
                'job_order_id' => $job_draft->job_order_id, // Correct reference
                'type' => 'content_writer',
                'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                'date_target' => Carbon::now()->addDays(3)->toDateString(),
                'status' => 'Waiting for Content Writer Approval',
                'content_writer_id' => $job_draft->content_writer_id,
                'graphic_designer_id' => $job_draft->graphic_designer_id,
                'client_id' => $job_draft->client_id,
                'signature_supervisor' => $job_draft->signature_supervisor,
                'supervisor_signed' => $job_draft->supervisor_signed
            ]);

            return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Approved Successfully');
        }
    }
}
