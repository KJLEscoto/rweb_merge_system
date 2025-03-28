<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobDraft;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopHistoryController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::whereNotIn('status', ['pending', 'Submitted to Assistant Supervisor', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
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
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'signature_supervisor' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'signature_pad' => 'nullable|string',
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

            if ($signatureCount === 0) {
                return redirect()->back()->withErrors(['signature' => 'A signature is required.'])->withInput();
            }

            if ($signatureCount > 1) {
                return redirect()->back()->withErrors(['signature' => 'Only one signature is allowed.'])->withInput();
            }

            $job_draft = JobDraft::findOrFail($id);
            $imagePath = $job_draft->signature_supervisor;

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
                        'status' => 'Submitted to Top Management',
                    ]);
                } elseif ($job_draft->works == "Both") {
                    $job_draft->update([
                        'status' => 'completed',
                    ]);
                    JobDraft::create([
                        'job_order_id' => $job_draft->job_order_id,
                        'type' => 'graphic_designer',
                        'date_started' => Carbon::now()->toDateString(),
                        'date_target' => Carbon::now()->addDays(3)->toDateString(),
                        'status' => 'Waiting for Graphic Designer Approval',
                        'content_writer_id' => $job_draft->content_writer_id,
                        'graphic_designer_id' => $job_draft->graphic_designer_id,
                        'client_id' => $job_draft->client_id,
                        'reference_draft_id' => $id,
                        'signature_supervisor' => $job_draft->signature_supervisor,
                        'supervisor_signed' => $job_draft->supervisor_signed,
                        'works' => $job_draft->works,
                    ]);
                }
            } elseif ($job_draft->type == "graphic_designer") {
                $job_draft->update([
                    'status' => 'Submitted to Top Management',
                ]);
            }

            $notificationController = new NotificationController();
            $sending_user = [];

            if ($job_draft->where('status', 'like', '%completed%')->where('type', 'content_writer')->exists()) {
                $sending_user = [
                    'content' => $job_draft->content_writer_id,
                ];
            } elseif ($job_draft->where('status', 'like', '%completed%')->where('type', 'graphic_designer')->exists()) {
                $sending_user = [
                    'graphic' => $job_draft->graphic_designer_id,
                ];
            }

            $notificationRequest = new Request([
                'job_order_id' => $job_draft->job_order_id,
                'from_user_id' => auth()->user()->id,
                'to_user_id' => $sending_user,
                'title' => $job_draft->jobOrder->title,
                'type' => 'admin.smm.approved.job-order',
                'month' => Carbon::now()->format('m'),
                'year' => Carbon::now()->format('Y'),
                'message' => $job_draft->draft,
            ]);

            $notificationController->sendAdminNotification($notificationRequest);

            DB::commit();

            return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Approved Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job Draft Update Failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the job draft.'])->withInput();
        }
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

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => auth()->user()->id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.rejected.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $request->summary,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Declined Successfully');
    }
}
