<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\JobOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DirectJobOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner')->get();
        return view('admin.smm.directjob.index', compact('job_drafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphicworkers = User::with('roles')->whereNotIn('role_id', [1, 3, 5])->get();
        $contentworkers = User::with('roles')->whereNotIn('role_id', [1, 4, 5])->get();

        return view('admin.smm.directjob.create', compact('clients', 'graphicworkers', 'contentworkers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        try {
            DB::beginTransaction();

            // Create job order
            $job_order = JobOrder::create([
                'title' => $request->title,
                'description' => $request->description,
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
            $notificationRequest = new Request([
                'job_order_id' => $job_draft->job_order_id,
                'from_user_id' => auth()->user()->id,
                'to_user_id' => ['content' => $content_writer_id, 'graphic' => $graphic_designer_id], // for multiple users
                'title' => $job_order->title,
                'type' => 'admin.smm.create.job-order',
                'month' => Carbon::now()->format('m'), // 'm' gives zero-padded month (e.g., 03 for March)
                'year' => Carbon::now()->format('Y'), // 'Y' gives full 4-digit year (e.g., 2025)
                'message' => $job_order->description,
            ]);

            $notify = $notificationController->sendAdminNotification($notificationRequest);

            DB::commit();

            // Determine the route based on the user's role
            $role = Auth::user()->roles->position;

            if ($role == 'top_management') {
                return redirect()->route('admin.smm.topmanager.directjob')
                    ->with('Status', 'Job Order Created Successfully');
            } elseif ($role == 'operations supervisor') {
                return redirect()->route('admin.smm.supervisor.directjob')
                    ->with('Status', 'Job Order Created Successfully');
            } else {
                // Default redirect if role is not recognized
                return redirect()->route('home') // or any default route you prefer
                    ->with('Status', 'Job Order Created Successfully');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // Log the error or handle it appropriately
            return back()->withErrors(['error' => 'Failed to create Job Order. Please try again. ' . $e->getMessage()])->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('admin.smm.directjob.show', compact('job_draft'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clients = User::with('roles')->where('role_id', 1)->get();
        $graphicworkers = User::with('roles')->whereNotIn('role_id', [1, 3])->get();
        $contentworkers = User::with('roles')->whereNotIn('role_id', [1, 4])->get();
        $job_draft = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        return view('admin.smm.directjob.edit', compact('job_draft', 'graphicworkers', 'contentworkers', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        // Find the job draft by ID
        $job_draft = JobDraft::findOrFail($id);

        $updateDraft = [
            'date_started' => $request->date_started,
            'date_target' => $request->date_target,
        ];

        // Conditionally update graphic_designer_id, client_id, and content_writer_id if provided
        if ($request->filled('graphic_designer_id')) {
            $updateDraft['graphic_designer_id'] = $request->graphic_designer_id;
        }

        if ($request->filled('client_id')) {
            $updateDraft['client_id'] = $request->client_id;
        }

        if ($request->filled('content_writer_id')) {
            $updateDraft['content_writer_id'] = $request->content_writer_id;
        }

        $job_draft->update($updateDraft);

        // Find the related job order
        $job_order = JobOrder::findOrFail($job_draft->job_order_id);

        // Update the job order
        $job_order->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Determine the route based on the user's role
        $role = Auth::user()->positions->position;

        if ($role == 'top_management') {
            return redirect()->route('admin.smm.topmanager.directjob.index')
                ->with('Status', 'Job Order Updated Successfully');
        } elseif ($role == 'operations supervisor') {
            return redirect()->route('admin.smm.supervisor.directjob.index')
                ->with('Status', 'Job Order Updated Successfully');
        } else {
            // Default redirect if role is not recognized
            return redirect()->route('home') // or any default route you prefer
                ->with('Status', 'Job Order Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
