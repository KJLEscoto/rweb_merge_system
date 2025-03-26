<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminSupervisorRequestController extends Controller
{
    public function index()
    {
        // Get supervisor requests that are NOT used in any job orders or job drafts
        $supervisor_requests = ModelsRequest::where('assigned_to', auth()->user()->id)
            ->whereDoesntHave('jobOrders') // Exclude requests already assigned to JobOrders
            ->get();

        return view('admin.smm.admin.supervisorRequest.index', compact('supervisor_requests'));
    }



    public function show($id)
    {
        $supervisor_request = ModelsRequest::find($id);
        return view('admin.smm.admin.supervisorRequest.show', compact('supervisor_request'));
    }

    public function create($id)
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphic_designers = User::with('roles')->whereNotIn('role_id', [1, 3, 5])->get();
        $content_writers = User::with('roles')->whereNotIn('role_id', [1, 4, 5])->get();

        $supervisor_request = ModelsRequest::find($id);

        return view('admin.smm.admin.supervisorRequest.create', compact('content_writers', 'graphic_designers', 'clients', 'supervisor_request'));
    }

    public function store(Request $request)
    {
        // Validate request before proceeding
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'content_writer_id' => 'required_without:graphic_designer_id',
            'graphic_designer_id' => 'required_without:content_writer_id',
            'client_id' => 'required|integer|exists:users,id',
            'date_started' => 'required|date',
            'date_target' => 'required|date',
            'content_checkbox' => 'required_without:graphic_checkbox',
            'graphic_checkbox' => 'required_without:content_checkbox',
            'request_id' => 'required'
        ]);

        // Determine work type and assign appropriate fields
        $work = null;
        $content_writer_id = null;
        $graphic_designer_id = null;
        $work_type = null;
        $initial_status = null;

        if ($request->content_checkbox && !$request->graphic_checkbox) {
            $work = "Content Only";
            $content_writer_id = $request->content_writer_id;
            $work_type = 'content_writer';
            $initial_status = 'Waiting for Content Writer Approval';
        } elseif ($request->graphic_checkbox && !$request->content_checkbox) {
            $work = "Graphic Only";
            $graphic_designer_id = $request->graphic_designer_id;
            $work_type = 'graphic_designer';
            $initial_status = 'Waiting for Graphic Designer Approval';
        } elseif ($request->content_checkbox && $request->graphic_checkbox) {
            $work = "Both";
            $content_writer_id = $request->content_writer_id;
            $graphic_designer_id = $request->graphic_designer_id;
            $work_type = 'content_writer';
            $initial_status = 'Waiting for Content Writer Approval';
        }

        // Create job order
        $job_order = JobOrder::create([
            'title' => $request->title,
            'description' => $request->description,
            'request_id' => $request->request_id,
            'issued_by' => auth()->user()->id,
        ]);

        // Create job draft
        $job_draft = JobDraft::create([
            'job_order_id' => $job_order->id,
            'type' => $work_type,
            'date_started' => $request->date_started,
            'date_target' => $request->date_target,
            'status' => $initial_status,
            'content_writer_id' => $content_writer_id,
            'graphic_designer_id' => $graphic_designer_id,
            'client_id' => $request->client_id,
            'signature_supervisor' => auth()->user()->signature,
            'supervisor_signed' => auth()->user()->id,
            'works' => $work
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $job_draft->job_order_id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['admin' => $job_draft->jobOrder->issed_by, 'content' => $job_draft->content_writer_id, 'graphic' => $job_draft->graphic_designer_id], // for multiple users
            'title' => $job_draft->jobOrder->title,
            'type' => 'admin.smm.create.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $request->summary,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.operation.request')->with('Status', 'Job Order Create Successfully');
    }

    public function accept($id)
    {

        $model_request = ModelsRequest::find($id);
        $model_request->update([ // Use ModelsRequest instead of Request
            'status' => 'Approved by Operation'
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $model_request->id,
            'from_user_id' => $model_request->assigned_to,
            'to_user_id' => ['content' => $model_request->issued_by], // for multiple users
            'title' => $model_request->title,
            'type' => 'admin.smm.accept.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $model_request->description,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.operation.request')->with('Status', 'Job Order Accepted Successfully');
    }
}
