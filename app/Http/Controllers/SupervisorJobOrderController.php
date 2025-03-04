<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Request as ModelsRequest; // Alias to avoid conflict
use App\Models\User;
use Illuminate\Http\Request;

class SupervisorJobOrderController extends Controller
{
    public function index()
    {
        $supervisor_requests = ModelsRequest::with('assignee')
            ->doesntHave('jobOrders') // Requests that have no job orders
            ->get();

        return view('pages.supervisor.job_order.index', compact('supervisor_requests'));
    }

    public function create()
    {
        $operators = User::where('role_id', 2)->get();
        return view('pages.supervisor.job_order.create', compact('operators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'assigned_to' => 'required|integer|exists:users,id',
            'deadline' => 'required|date'
        ]);

        ModelsRequest::create([ // Use ModelsRequest instead of Request
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'issued_by' => auth()->user()->id,
            'status' => 'Waiting for Operation Approval',
            'deadline' => $request->deadline
        ]);

        return redirect()->route('supervisor.joborder')->with('Status', 'Job Order Created Successfully');
    }

    public function show($id)
    {
        $supervisor_request = ModelsRequest::with('issuer', 'assignee')->find($id);
        return view('pages.supervisor.job_order.show', compact('supervisor_request'));
    }

    public function edit($id)
    {
        $supervisor_request = ModelsRequest::with('issuer', 'assignee')->find($id);
        $operators = User::where('role_id', 2)->get();
        return view('pages.supervisor.job_order.edit', compact('supervisor_request', 'operators'));
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
        return redirect()->route('supervisor.joborder')->with('Status', 'Job Order Updated Successfully');
    }
}
