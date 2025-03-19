<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\JobOrder;
use App\Models\Signature;
use App\Models\User;
use App\Models\WebJobOrder;
use App\Models\WebProject;
use App\Models\WebProjectChannel;
use App\Models\WebRevisions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $web_project_channels = null;

        switch ($user->roles->position) {
            case "supervisor":
                $web_project_channels = WebProjectChannel::with('web_project')->where('status', 'Submitted to Supervisor')->get();
                break;
            case "top_manager":
                $web_project_channels = WebProjectChannel::with('web_project')->where('status', 'Submitted to Top Manager')->get();
                break;
            case "client":
                $web_project_channels = WebProjectChannel::with('web_project')->where('status', 'Submitted to Client')->get();
                break;
            case "operations":
                $web_project_channels = WebProjectChannel::with('web_project')->where('status', 'Submitted to Operation')->get();
                break;
            default:
                return back()->with('Status', 'Invalid role!');
        }

        return view('admin.web-development.approvals.index', compact('web_project_channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $web_project_channel = null;

        switch ($user->roles->position) {
            case "supervisor":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Supervisor')->find($id);
                break;
            case "top_manager":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Top Manager')->find($id);
                break;
            case "client":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Client')->find($id);
                break;
            case "operations":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Operation')->find($id);
                break;
            default:
                return back()->with('Status', 'Invalid role!');
        }

        return view('admin.web-development.approvals.show', compact('web_project_channel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function decline(Request $request, $id)
    {
        WebProjectChannel::find($id)->update(['status' => 'Revision']);
        WebRevisions::create([
            'web_project_channel_id' => $id,
            'declined_by_id' => auth()->user()->id,
            'summary' => $request->summary,
            'last_draft' => WebProjectChannel::find($id)->draft,
            'latest_draft' => null,
            'date_submitted' => null,
        ]);

        return redirect()->route('admin.web.approvals')->with('status', 'Declined Successfully');
    }

    public function approve(Request $request, $id, FileController $fileController)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $data = $request->validate([
                'new_signature_pad' => 'nullable',
                'signature_pad' => 'nullable',
                'signature_admin' => 'nullable',
            ]);

            if ($request->signature_pad != null) {
                // Extract base64 string
                $base64String = $data['signature_pad']; // Full base64 string

                // Decode base64
                $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));

                // Define a temporary path
                $tempPath = storage_path('app/temp_signature.png');

                // Save the file temporarily
                file_put_contents($tempPath, $fileData);

                // Convert to UploadedFile
                $file = new UploadedFile(
                    $tempPath,
                    'signature.png', // File name
                    'image/png', // MIME Type
                    null, // Error (null means no error)
                    true // Test mode (prevents actual file validation issues)
                );

                $signature_file_id = $user->signatures->file_id;
                $file_id = File::where('id', $signature_file_id)->first()->description;
                $fileController->edit(new Request(['file' => $file]), $file_id);
            }


            if ($request->signature_admin != null) {
                $signature_file_id = $user->signatures->file_id;
                $file_id = File::where('id', $signature_file_id)->first()->description;
                $file_object = $fileController->edit(new Request(['file' => $data['signature_admin']]), $file_id);
            }


            $web_project_channel = null;
            $web_project_channel = WebProjectChannel::find($id);

            switch ($user->roles->position) {
                case "supervisor":
                    $web_project_channel->status = "Submitted to Top Manager";
                    $web_project_channel->web_job_orders->update([
                        'supervisor_signed_draft_id' => Auth::user()->id,
                    ]);
                    break;
                case "top_manager":
                    $web_project_channel->status = 'Submitted to Client';
                    break;
                case "client":
                    // Check if web designers have completed their tasks
                    if ($web_project_channel->where('type', 'web_designer')->where('status', 'Submitted to Client')->exists()) {
                        if ($web_project_channel->status == 'Submitted to Client' && $web_project_channel->sub_status == 'Site Map') {

                            $web_project_channel->status = "Completed";
                            $web_project_channel->date_completed = Carbon::now();
                            $web_project_channel->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);

                            $job_order = WebJobOrder::create([
                                'operation_signed_draft_id' => null,
                                'supervisor_signed_draft_id' => null,
                                'client_signed_id' => null,
                                'status' => 'Job order for ' . User::where('id', $web_project_channel->user_id)->first()->name,
                            ]);

                            $project_channel = WebProjectChannel::create([
                                'user_id' => $web_project_channel->user_id,
                                'web_job_order_id' => $job_order->id,
                                'feedback_id' => null,
                                'project_id' => $web_project_channel->project_id,
                                'status' => 'pending',
                                'sub_status' => 'Draft Homepage Approval',
                                'type' => $web_project_channel->type,
                                'draft' => null,
                                'date_started' => Carbon::now(),
                                'date_targeted' => Carbon::now()->addDays(3),
                                'date_completed' => null,
                            ]);

                            $web_designers = $web_project_channel->where('type', 'web_designer')
                                ->where('sub_status', 'like', '%Site Map%')
                                ->whereNotIn('status', ['declined', 'pending', 'completed'])
                                ->get();

                            foreach ($web_designers as $web_designer) {
                                if ($web_project_channel->user_id == $web_designer->user_id) {
                                    continue;
                                }
                                $web_designer->update([
                                    'status' => 'Completed',
                                    'date_completed' => Carbon::now(),
                                ]);

                                $web_designer->web_job_orders->update([
                                    'client_signed_id' => Auth::user()->id,
                                ]);

                                $job_order = WebJobOrder::create([
                                    'operation_signed_draft_id' => null,
                                    'supervisor_signed_draft_id' => null,
                                    'client_signed_id' => null,
                                    'status' => 'Job order for ' . User::where('id', $web_designer->user_id)->first()->name,
                                ]);

                                $project_channel = WebProjectChannel::create([
                                    'user_id' => $web_designer->user_id,
                                    'web_job_order_id' => $job_order->id,
                                    'feedback_id' => null,
                                    'project_id' => $web_designer->project_id,
                                    'status' => 'pending',
                                    'sub_status' => 'Draft Homepage Approval',
                                    'type' => $web_designer->type,
                                    'draft' => null,
                                    'date_started' => Carbon::now(),
                                    'date_targeted' => Carbon::now()->addDays(3),
                                    'date_completed' => null,
                                ]);
                            }
                        } else if ($web_project_channel->status == 'Submitted to Client' && $web_project_channel->sub_status == 'Draft Homepage Approval') {

                            $web_project_channel->status = "Completed";
                            $web_project_channel->date_completed = Carbon::now();
                            $web_project_channel->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);

                            $job_order = WebJobOrder::create([
                                'operation_signed_draft_id' => null,
                                'supervisor_signed_draft_id' => null,
                                'client_signed_id' => null,
                                'status' => 'Job order for ' . User::where('id', $web_project_channel->user_id)->first()->name,
                            ]);

                            $project_channel = WebProjectChannel::create([
                                'user_id' => $web_project_channel->user_id,
                                'web_job_order_id' => $job_order->id,
                                'feedback_id' => null,
                                'project_id' => $web_project_channel->project_id,
                                'status' => 'pending',
                                'sub_status' => 'Final Homepage Approval',
                                'type' => $web_project_channel->type,
                                'draft' => null,
                                'date_started' => Carbon::now(),
                                'date_targeted' => Carbon::now()->addDays(3),
                                'date_completed' => null,
                            ]);

                            $web_designers = $web_project_channel->where('type', 'web_designer')
                                ->where('sub_status', 'like', '%Draft Homepage Approval%')
                                ->whereNotIn('status', ['declined', 'pending', 'completed'])
                                ->get();

                            foreach ($web_designers as $web_designer) {
                                if ($web_project_channel->user_id == $web_designer->user_id) {
                                    continue;
                                }
                                $web_designer->update([
                                    'status' => 'Completed',
                                    'date_completed' => Carbon::now(),
                                ]);

                                $web_designer->web_job_orders->update([
                                    'client_signed_id' => Auth::user()->id,
                                ]);

                                $job_order = WebJobOrder::create([
                                    'operation_signed_draft_id' => null,
                                    'supervisor_signed_draft_id' => null,
                                    'client_signed_id' => null,
                                    'status' => 'Job order for ' . User::where('id', $web_designer->user_id)->first()->name,
                                ]);

                                $project_channel = WebProjectChannel::create([
                                    'user_id' => $web_designer->user_id,
                                    'web_job_order_id' => $job_order->id,
                                    'feedback_id' => null,
                                    'project_id' => $web_designer->project_id,
                                    'status' => 'pending',
                                    'sub_status' => 'Final Homepage Approval',
                                    'type' => $web_designer->type,
                                    'draft' => null,
                                    'date_started' => Carbon::now(),
                                    'date_targeted' => Carbon::now()->addDays(3),
                                    'date_completed' => null,
                                ]);
                            }
                        } else if ($web_project_channel->status == 'Submitted to Client' && $web_project_channel->sub_status == 'Final Homepage Approval') {

                            $web_project_channel->status = "Completed";
                            $web_project_channel->date_completed = Carbon::now();
                            $web_project_channel->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);

                            $job_order = WebJobOrder::create([
                                'operation_signed_draft_id' => null,
                                'supervisor_signed_draft_id' => null,
                                'client_signed_id' => null,
                                'status' => 'Job order for ' . User::where('id', $web_project_channel->user_id)->first()->name,
                            ]);

                            $project_channel = WebProjectChannel::create([
                                'user_id' => $web_project_channel->user_id,
                                'web_job_order_id' => $job_order->id,
                                'feedback_id' => null,
                                'project_id' => $web_project_channel->project_id,
                                'status' => 'pending',
                                'sub_status' => 'All Pages Approval',
                                'type' => $web_project_channel->type,
                                'draft' => null,
                                'date_started' => Carbon::now(),
                                'date_targeted' => Carbon::now()->addDays(3),
                                'date_completed' => null,
                            ]);

                            $web_designers = $web_project_channel->where('type', 'web_designer')
                                ->where('sub_status', 'like', '%Final Homepage Approval%')
                                ->whereNotIn('status', ['declined', 'pending', 'completed'])
                                ->get();

                            foreach ($web_designers as $web_designer) {
                                if ($web_project_channel->user_id == $web_designer->user_id) {
                                    continue;
                                }
                                $web_designer->update([
                                    'status' => 'Completed',
                                    'date_completed' => Carbon::now(),
                                ]);

                                $web_designer->web_job_orders->update([
                                    'client_signed_id' => Auth::user()->id,
                                ]);

                                $job_order = WebJobOrder::create([
                                    'operation_signed_draft_id' => null,
                                    'supervisor_signed_draft_id' => null,
                                    'client_signed_id' => null,
                                    'status' => 'Job order for ' . User::where('id', $web_designer->user_id)->first()->name,
                                ]);

                                $project_channel = WebProjectChannel::create([
                                    'user_id' => $web_designer->user_id,
                                    'web_job_order_id' => $job_order->id,
                                    'feedback_id' => null,
                                    'project_id' => $web_designer->project_id,
                                    'status' => 'pending',
                                    'sub_status' => 'All Pages Approval',
                                    'type' => $web_designer->type,
                                    'draft' => null,
                                    'date_started' => Carbon::now(),
                                    'date_targeted' => Carbon::now()->addDays(3),
                                    'date_completed' => null,
                                ]);
                            }
                        } else if ($web_project_channel->status == 'Submitted to Client' && $web_project_channel->sub_status == 'All Pages Approval') {

                            $web_project_channel->status = "Completed";
                            $web_project_channel->date_completed = Carbon::now();
                            $web_project_channel->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);

                            $web_designers = $web_project_channel->where('type', 'web_designer')
                                ->where('sub_status', 'like', '%All Pages Approval%')
                                ->whereNotIn('status', ['declined', 'pending', 'completed'])
                                ->get();

                            foreach ($web_designers as $web_designer) {
                                if ($web_project_channel->user_id == $web_designer->user_id) {
                                    continue;
                                }
                                $web_designer->update([
                                    'status' => 'Completed',
                                    'date_completed' => Carbon::now(),
                                ]);

                                $web_designer->web_job_orders->update([
                                    'client_signed_id' => Auth::user()->id,
                                ]);
                            }

                            $front_ends = $web_project_channel->where('type', 'front_end')
                                ->where('status', 'pending')
                                ->get();


                            foreach ($front_ends as $front_end) {
                                $front_end->update([
                                    'date_started' => Carbon::now(),
                                    'date_targeted' => Carbon::now()->addDays(3),
                                ]);
                            }
                        }
                    } elseif ($web_project_channel->where('type', 'front_end')->where('status', 'Submitted to Client')->exists()) {
                        $web_project_channel->status = "Completed";
                        $web_project_channel->date_completed = Carbon::now();
                        $web_project_channel->web_job_orders->update([
                            'client_signed_id' => Auth::user()->id,
                        ]);

                        $front_ends = $web_project_channel->where('type', 'front_end')
                            ->whereNotIn('status', ['declined', 'pending'])
                            ->get();

                        foreach ($front_ends as $front_end) {
                            $front_end->update([
                                'status' => 'Completed',
                                'date_completed' => Carbon::now(),
                            ]);

                            $front_end->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);
                        }

                        $back_ends = $web_project_channel->where('type', 'back_end')
                            ->where('status', 'pending')
                            ->get();

                        foreach ($back_ends as $backend_end) {
                            $backend_end->update([
                                'date_started' => Carbon::now(),
                                'date_targeted' => Carbon::now()->addDays(3),
                            ]);
                        }
                    } elseif ($web_project_channel->where('type', 'back_end')->where('status', 'Submitted to Client')->exists()) {
                        $web_project_channel->status = "Completed";
                        $web_project_channel->date_completed = Carbon::now();
                        $web_project_channel->web_job_orders->update([
                            'client_signed_id' => Auth::user()->id,
                        ]);

                        $back_ends = $web_project_channel->where('type', 'back_end')
                            ->whereNotIn('status', ['declined', 'pending'])
                            ->get();

                        foreach ($back_ends as $back_end) {
                            $back_end->update([
                                'status' => 'Completed',
                                'date_completed' => Carbon::now(),
                            ]);
                            $back_end->web_job_orders->update([
                                'client_signed_id' => Auth::user()->id,
                            ]);
                        }
                    }
                    break;
                case "operations":
                    $web_project_channel->status = "Submitted to Supervisor";
                    $web_project_channel->web_job_orders->update([
                        'operation_signed_draft_id' => Auth::user()->id,
                    ]);

                    break;
                default:
                    return back()->with('Status', 'Invalid roles!');
            }

            $web_project_channel->save();
            $web_project_channel->fresh();

            //@dd('stop last', WebProjectChannel::all());

            DB::commit();

            return redirect()->route('admin.web.approvals')->with('success', 'Congrats');
        } catch (\Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
        }
    }
}