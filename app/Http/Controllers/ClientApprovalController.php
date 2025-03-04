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

        return view('pages.client.joborder.list', compact('job_drafts'));
    }

    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.client.joborder.show', compact('job_draft'));
    }

    public function edit($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.client.joborder.edit', compact('job_draft'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'summary' => 'required',
        ]);

        $job_draft_id = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        $job_draft_id->update([
            'feedback' => $request->summary,
            'status' => 'completed',
            'date_completed' => now(),
        ]);

        if ($job_draft_id->type == 'content_writer') {
            JobDraft::create([
                'job_order_id' => $job_draft_id->job_order_id,
                'type' => 'graphic_designer',
                // 'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                // 'date_target' => Carbon::now()->addDays(3)->toDateString(),
                'status' => 'Waiting for Graphic Designer Approval',
                'content_writer_id' => $job_draft_id->content_writer_id,
                'graphic_designer_id' => $job_draft_id->graphic_designer_id,
                'client_id' => $job_draft_id->client_id,
                'reference_draft_id' => $id,
                'signature_supervisor' => $job_draft_id->signature_supervisor,
                'supervisor_signed' => $job_draft_id->supervisor_signed
            ]);
        } elseif ($job_draft_id->type == 'graphic_designer') {
            if ($job_draft_id->jobOrder->renewable == 0) {
                return view('pages.client.joborder.renew', compact('job_draft_id'));
            } elseif ($job_draft_id->jobOrder->renewable == 1) {
                JobDraft::create([
                    'job_order_id' => $job_draft_id->job_order_id,
                    'type' => 'content_writer',
                    // 'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                    // 'date_target' => Carbon::now()->addDays(3)->toDateString(),
                    'status' => 'Waiting for Content Writer Approval',
                    'content_writer_id' => $job_draft_id->content_writer_id,
                    'graphic_designer_id' => $job_draft_id->graphic_designer_id,
                    'client_id' => $job_draft_id->client_id,
                    'signature_supervisor' => $job_draft_id->signature_supervisor,
                    'supervisor_signed' => $job_draft_id->supervisor_signed
                ]);
            }
        }
        return redirect()->route('client.approve')->with('Status', 'Job Order Approved Successfully');
    }
    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.client.joborderapproval.declineform', compact('job_draft'));
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
        return redirect()->route('client.approve')->with('Status', 'Job Order Declined Successfully');
    }

    public function renew(Request $request, $id)
    {
        if ($request->renewable == 0) {
            return redirect()->route('client.approve')->with('Status', 'Job Order Approved Successfully');
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
                // 'date_started' => Carbon::now()->toDateString(), // Set date_started to today
                // 'date_target' => Carbon::now()->addDays(3)->toDateString(),
                'status' => 'Waiting for Content Writer Approval',
                'content_writer_id' => $job_draft->content_writer_id,
                'graphic_designer_id' => $job_draft->graphic_designer_id,
                'client_id' => $job_draft->client_id,
                'signature_supervisor' => $job_draft->signature_supervisor,
                'supervisor_signed' => $job_draft->supervisor_signed
            ]);

            return redirect()->route('client.approve')->with('Status', 'Job Order Approved Successfully');
        }
    }
}
