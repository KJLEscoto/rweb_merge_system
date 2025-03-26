<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Request as ModelsRequest; // Alias to avoid conflict
use App\Models\User;
use App\Models\WebRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupervisorJobOrderController extends Controller
{
    public function index()
    {
        $supervisor_requests = ModelsRequest::with('assignee')
            ->get();

        return view('admin.smm.supervisor.job_order.index', compact('supervisor_requests'));
    }

    public function create()
    {
        $operators = User::where('role_id', 2)->get();
        return view('admin.smm.supervisor.job_order.create', compact('operators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'assigned_to' => 'required|integer|exists:users,id',
            'deadline' => 'required|date'
        ]);

        $modelsrequest = ModelsRequest::create([ // Use ModelsRequest instead of Request
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'issued_by' => auth()->user()->id,
            'status' => 'Waiting for Operation Approval',
            'deadline' => $request->deadline
        ]);

        $notificationController = new NotificationController();

        //formulate the data in the notification
        $request = new Request([
            'job_order_id' => $modelsrequest->id,
            'from_user_id' => auth()->user()->id,
            'to_user_id' => ['content' => $modelsrequest->assigned_to], // for multiple users
            'title' => $modelsrequest->title,
            'type' => 'admin.smm.request.job-order',
            'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
            'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
            'message' => $request->summary,
        ]);

        $notify = $notificationController->sendAdminNotification($request);

        return redirect()->route('admin.smm.supervisor.joborder')->with('Status', 'Job Order Created Successfully');
    }

    public function show($id)
    {
        $supervisor_request = ModelsRequest::with('issuer', 'assignee')->find($id);
        return view('admin.smm.supervisor.job_order.show', compact('supervisor_request'));
    }

    public function edit($id)
    {
        $supervisor_request = ModelsRequest::with('issuer', 'assignee')->find($id);
        $operators = User::where('role_id', 2)->get();
        return view('admin.smm.supervisor.job_order.edit', compact('supervisor_request', 'operators'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'assigned_to' => 'sometimes|string',
            'deadline' => 'sometimes|date'
        ]);

        $modelsrequest = ModelsRequest::find($id);

        $modelsrequest->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'deadline' => $request->deadline
        ]);

        return redirect()->route('admin.smm.supervisor.joborder')->with('Status', 'Job Order Updated Successfully');
    }

    public function delete($id)
    {
        ModelsRequest::find($id)->delete();
        return redirect()->back();
    }
}
