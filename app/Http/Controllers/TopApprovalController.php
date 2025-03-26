<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\Revision;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopApprovalController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::where('status', 'Submitted to Top Management')
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

        // Update Database with Signature Path
        $job_draft->update([
            'status' => 'Submitted to Client',
        ]);

        $notificationController = new NotificationController();

        $sending_user = [];

        if ($job_draft->where('status', 'like', '%completed%')->where('type', 'content_writer')->exists()) {
            $sending_user = [
                'content' => $job_draft->content_writer_id,
                'client' => $job_draft->client_id,
            ];
        } elseif ($job_draft->where('status', 'like', '%completed%')->where('type', 'graphic_designer')->exists()) {
            $sending_user = [
                'graphic' => $job_draft->graphic_designer_id,
                'client' => $job_draft->client_id,
            ];
        }

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => $sending_user, // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.approved.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $job_draft->draft,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Approved Successfully');
    }

    public function declineForm($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.topmanager.joborderapproval.declineform', compact('job_draft'));
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

            if ($job_draft == 'content_writer') {
                $send_user = ['content' => $job_draft->content_writer_id, 'client' => $job_draft->client_id];
            } else {
                $send_user = ['graphic' => $job_draft->graphic_designer_id, 'client' => $job_draft->client_id];
            }

            $notificationRequest = new Request([
                'job_order_id' => $job_draft->job_order_id,
                'from_user_id' => auth()->user()->id,
                'to_user_id' => $send_user,
                'title' => $job_draft->jobOrder->title,
                'type' => 'admin.smm.rejected.job-order',
                'month' => Carbon::now()->format('m'),
                'year' => Carbon::now()->format('Y'),
                'message' => $request->summary,
            ]);

            $notificationController->sendAdminNotification($notificationRequest);

            DB::commit();

            return redirect()->route('admin.smm.topmanager.approve')->with('Status', 'Job Order Declined Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job Draft Decline Failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while declining the job draft.'])->withInput();
        }
    }
}
