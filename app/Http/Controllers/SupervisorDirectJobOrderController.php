<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\User;
use Illuminate\Http\Request;

class SupervisorDirectJobOrderController extends Controller
{
    public function index()
    {
        $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner')->get();
        return view('admin.smm.supervisor.directjob.index', compact('job_drafts'));
    }

    public function create()
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphicworkers = User::with('roles')->whereNotIn('role_id', [1, 3, 5])->get();
        $contentworkers = User::with('roles')->whereNotIn('role_id', [1, 4, 5])->get();

        return view('admin/smm/supervisor/directjob/create', compact('clients', 'graphicworkers', 'contentworkers'));
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
            'graphic_checkbox' => 'required_without:content_checkbox'
        ]);

        // Ensure the user has a signature before proceeding
        if (!auth()->user()->signature) {
            return redirect()->route('admin.smm.supervisor.directjob.create')
                ->with('Status', 'No Signature Found');
        }

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
            'issued_by' => auth()->user()->id,
        ]);

        // Create job draft
        JobDraft::create([
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

        return redirect()->route('admin.smm.supervisor.directjob')
            ->with('Status', 'Job Order Created Successfully');
    }



    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('admin/smm/supervisor/directjob/show', compact('job_draft'));
    }

    public function edit($id)
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphicworkers = User::with('roles')->whereNotIn('role_id', [1, 3])->get();
        $contentworkers = User::with('roles')->whereNotIn('role_id', [1, 4])->get();
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('admin/smm/supervisor/directjob/edit', compact('job_draft', 'graphicworkers', 'contentworkers', 'clients'));
    }

    public function update(Request $request, $id)
    {
        // Validate request before proceeding
        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'content_writer_id' => 'sometimes|integer',
            'graphic_designer_id' => 'sometimes|integer|nullable',
            'client_id' => 'sometimes|integer|nullable',
            'date_target' => 'sometimes|date',
            'date_started' => 'sometimes|date'
        ]);

        // Find the job order by ID
        $job_draft = JobDraft::findOrFail($id);

        $updateDraft =
            [
                'date_started' => $request->date_started,
                'date_target' => $request->date_target,
            ];

        // Conditionally update graphic_designer_id and client_id if provided
        if ($request->has('graphic_designer_id') && $request->graphic_designer_id !== null) {
            $updateDraft['graphic_designer_id'] = $request->graphic_designer_id;
        }

        if ($request->has('client_id') && $request->client_id !== null) {
            $updateDraft['client_id'] = $request->client_id;
        }

        if ($request->has('content_writer_id') && $request->content_writer_id !== null) {
            $updateDraft['content_writer_id'] = $request->content_writer_id;
        }

        $job_draft->update($updateDraft);
        // Find the related job draft
        $job_order = JobOrder::findOrFail($job_draft->job_order_id);
        // Update the job order
        $job_order->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.smm.supervisor.directjob')->with('Status', 'Job Order Updated Successfully');
    }
}
