<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'signature_client' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'signature_pad' => 'nullable|string',
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

            if ($signatureCount === 0) {
                return redirect()->back()->withErrors(['signature' => 'A signature is required.'])->withInput();
            }

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

            $job_draft->update([
                'feedback' => $request->summary,
                'status' => 'completed',
                'date_completed' => now(),
                'client_signature' => $imagePath,
            ]);

            if ($job_draft->type == 'graphic_designer') {
                $content_draft = JobDraft::where('id', $job_draft->reference_draft_id)->first();
                if ($content_draft) {
                    $content_draft->update([
                        'feedback' => $request->summary,
                        'date_completed' => now(),
                    ]);
                }
            }

            if ($job_draft->jobOrder->renewable == 1) {
                if ($job_draft->works == 'Content Only') {
                    JobDraft::create([
                        'job_order_id' => $job_draft->job_order_id,
                        'type' => 'content_writer',
                        'date_started' => Carbon::now()->addDays(15)->toDateString(),
                        'date_target' => Carbon::now()->addDays(18)->toDateString(),
                        'status' => 'Waiting for Content Writer Approval',
                        'content_writer_id' => $job_draft->content_writer_id,
                        'graphic_designer_id' => null,
                        'client_id' => $job_draft->client_id,
                        'signature_supervisor' => $job_draft->signature_supervisor,
                        'supervisor_signed' => $job_draft->supervisor_signed,
                        'works' => $job_draft->works,
                    ]);
                } elseif ($job_draft->works == 'Graphic Only') {
                    JobDraft::create([
                        'job_order_id' => $job_draft->job_order_id,
                        'type' => 'graphic_designer',
                        'date_started' => Carbon::now()->addDays(15)->toDateString(),
                        'date_target' => Carbon::now()->addDays(18)->toDateString(),
                        'status' => 'Waiting for Graphic Designer Approval',
                        'content_writer_id' => null,
                        'graphic_designer_id' => $job_draft->graphic_designer_id,
                        'client_id' => $job_draft->client_id,
                        'signature_supervisor' => $job_draft->signature_supervisor,
                        'supervisor_signed' => $job_draft->supervisor_signed,
                        'works' => $job_draft->works,
                    ]);
                } elseif ($job_draft->works == 'Both') {
                    JobDraft::create([
                        'job_order_id' => $job_draft->job_order_id,
                        'type' => 'content_writer',
                        'date_started' => Carbon::now()->addDays(15)->toDateString(),
                        'date_target' => Carbon::now()->addDays(18)->toDateString(),
                        'status' => 'Waiting for Content Writer Approval',
                        'content_writer_id' => $job_draft->content_writer_id,
                        'graphic_designer_id' => $job_draft->graphic_designer_id,
                        'client_id' => $job_draft->client_id,
                        'signature_supervisor' => $job_draft->signature_supervisor,
                        'supervisor_signed' => $job_draft->supervisor_signed,
                        'works' => $job_draft->works,
                    ]);
                }
            }

            $notificationController = new NotificationController();

            $sendUser = [];

            if ($job_draft->type == 'content_writer') { // Corrected the comparison
                $sendUser = ['content' => $job_draft->content_writer_id];
            } else {
                $sendUser = ['graphic' => $job_draft->graphic_designer_id];
            }

            $notificationRequest = new Request([
                'job_order_id' => $job_draft->job_order_id,
                'from_user_id' => auth()->user()->id,
                'to_user_id' => $sendUser,
                'title' => $job_draft->jobOrder->title,
                'type' => 'admin.smm.approved.job-order',
                'month' => Carbon::now()->format('m'),
                'year' => Carbon::now()->format('Y'),
                'message' => $job_draft->draft,
            ]);

            $notificationController->sendAdminNotification($notificationRequest);

            DB::commit();

            return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Approved Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job Draft Update Failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the job draft.'])->withInput();
        }
    }
    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.client.joborderapproval.declineform', compact('job_draft'));
    }

    public function decline(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'summary' => 'required',
            ]);

            $job_draft = JobDraft::find($id);

            Revision::create([
                'job_draft_id' => $id,
                'declined_by' => auth()->user()->id,
                'summary' => $request->summary,
                'last_draft' => $job_draft->draft,
                'revision_date' => Carbon::now()->toDateString(),
                'status' => 'pending',
            ]);

            $job_draft->update([
                'status' => 'Revision',
                'draft_op_sign' => null,
                'op_signed_draft' => null,
                'draft_sup_sign' => null,
                'sup_signed_draft' => null,
            ]);

            $notificationController = new NotificationController();

            $send_user = [];
            if ($send_user == 'content_writer') {
                $send_user = ['content' => $job_draft->content_writer_id];
            } else {
                $send_user = ['graphic' => $job_draft->graphic_designer_id];
            }

            $notificationRequest = new Request([
                'from_user_id' => auth()->user()->id,
                'to_user_id' => ['content' => $job_draft->jobOrder->issued_by], // Send to the job order issuer
                'title' => $job_draft->jobOrder->title,
                'type' => 'admin.smm.rejected.job-order',
                'month' => Carbon::now()->format('m'),
                'year' => Carbon::now()->format('Y'),
                'message' => $request->summary,
            ]);

            $notificationController->sendAdminNotification($notificationRequest);

            DB::commit();

            return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Declined Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job Draft Decline Failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while declining the job draft.'])->withInput();
        }
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

            $notificationController = new NotificationController();

            //formulate the data in the notification
            $request = new Request([
                'from_user_id' => auth()->user()->id,
                'to_user_id' => ['content' => auth()->user()->id], // for multiple users
                'title' => $job_draft->jobOrder->title,
                'type' => 'admin.smm.renewal.job-order',
                'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
                'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
                'message' => $job_draft->job_order->description,
            ]);

            $notify = $notificationController->sendAdminNotification($request);

            return redirect()->route('admin.smm.client.approve')->with('Status', 'Job Order Approved Successfully');
        }
    }
}
