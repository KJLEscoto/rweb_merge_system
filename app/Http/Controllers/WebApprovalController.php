<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Page;
use App\Models\Privilege;
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
use Illuminate\Support\Facades\Log;
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

        // Check for role first
        if ($user->roles && $user->roles->position) {
            $web_project_channels = $this->getWebProjectChannelsByUserRole($user);
            if ($web_project_channels !== null) {
                return view('admin.web-development.approvals.index', compact('web_project_channels'));
            }
        }

        // If no role or invalid role, check for approval privileges
        if ($this->userHasApprovalPrivilege($user)) {
            // User has approval privilege, get all web projects by roles.
            $web_project_channels = $this->getAllWebProjectChannelsByRoles();
            if ($web_project_channels !== null) {
                return view('admin.web-development.approvals.index', compact('web_project_channels'));
            }
        }

        // If no role and no approval privileges, return error
        return back()->with('status', 'Invalid role or insufficient privileges.');
    }

    /**
     * Retrieve web project channels based on the user's role.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    private function getWebProjectChannelsByUserRole($user)
    {
        $rolePosition = $user->roles->position ?? null;

        if (!$rolePosition) {
            return null; // Invalid or missing role
        }

        $statusMappings = [
            'operations_supervisor' => 'Submitted to Operations Supervisor',
            'top_management' => 'Submitted to Top Management',
            'client' => 'Submitted to Client',
            'assistant_supervisor' => 'Submitted to Assistant Supervisor',
        ];

        if (array_key_exists($rolePosition, $statusMappings)) {
            return WebProjectChannel::with('web_project')
                ->where('status', $statusMappings[$rolePosition])
                ->get();
        }

        return null; // Role not found in mappings
    }

    /**
     * Retrieve all web project channels based on defined roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    private function getAllWebProjectChannelsByRoles()
    {
        $statusMappings = [
            'operations_supervisor' => 'Submitted to Operations Supervisor',
            'top_management' => 'Submitted to Top Management',
            'client' => 'Submitted to Client',
            'assistant_supervisor' => 'Submitted to Assistant Supervisor',
        ];

        $web_project_channels = WebProjectChannel::with('web_project')
            ->whereIn('status', array_values($statusMappings))
            ->get();

        return $web_project_channels->isNotEmpty() ? $web_project_channels : null;
    }

    /**
     * Check if the user has approval privileges using eager loading.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    private function userHasApprovalPrivilege($user): bool
    {
        $approvalPage = Page::where('description', 'like', '%approvals%')->first();
        $approvalPrivilege = Privilege::where('description', 'like', '%can_approve%')->first();

        if (!$approvalPage || !$approvalPrivilege) {
            return false;
        }

        $roleChannel = $user->role_channels()
            ->where('page_id', $approvalPage->id)
            ->where('privilege_id', $approvalPrivilege->id)
            ->first();

        return $roleChannel !== null;
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
            case "operations_supervisor":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Operations Supervisor')->find($id);
                break;
            case "top_management":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Top Management')->find($id);
                break;
            case "client":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Client')->find($id);
                break;
            case "assistant_supervisor":
                $web_project_channel = WebProjectChannel::with('web_project')->where('status', 'Submitted to Assistant Supervisor')->find($id);
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

            $this->handleSignatures($request, $data, $user, $fileController);

            $webProjectChannel = WebProjectChannel::find($id);
            if (!$webProjectChannel) {
                return back()->with('error', 'Web Project Channel not found.');
            }

            $this->processApproval($user, $webProjectChannel);

            $webProjectChannel->save();
            $webProjectChannel->fresh();

            DB::commit();

            return redirect()->route('admin.web.approvals')->with('success', 'Approval successful.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approval error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred during approval.');
        }
    }

    private function handleSignaturesGdrive(Request $request, array $data, User $user, FileController $fileController): void
    {
        if ($request->signature_pad) {
            $file = $this->convertBase64ToUploadedFile($data['signature_pad']);
            if ($user->signatures && $user->signatures->file_id) {
                $fileId = File::where('id', $user->signatures->file_id)->value('description');
                $fileController->edit(new Request(['file' => $file]), $fileId);
            }
        }

        if ($request->signature_admin && $user->signatures && $user->signatures->file_id) {
            $fileId = File::where('id', $user->signatures->file_id)->value('description');
            $fileController->edit(new Request(['file' => $data['signature_admin']]), $fileId);
        }
    }

    private function handleSignatures(Request $request, array $data, User $user, FileController $fileController): void
    {
        if ($request->signature_pad) {
            $file = $this->convertBase64ToUploadedFile($data['signature_pad']);
            if ($user->signatures && $user->signatures->file_id) {
                $fileId = File::where('id', $user->signatures->file_id)->value('id'); // Use 'id' instead of 'description'
                $fileController->edit(new Request(['file' => $file]), $fileId);
            }
        }

        if ($request->signature_admin && $user->signatures && $user->signatures->file_id) {
            $fileId = File::where('id', $user->signatures->file_id)->value('id'); // Use 'id' instead of 'description'
            $fileController->edit(new Request(['file' => $data['signature_admin']]), $fileId);
        }
    }

    private function convertBase64ToUploadedFile(string $base64String): UploadedFile
    {
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $tempPath = storage_path('app/temp_signature.png');
        file_put_contents($tempPath, $fileData);

        return new UploadedFile(
            $tempPath,
            'signature.png',
            'image/png',
            null,
            true
        );
    }

    private function processApproval(User $user, WebProjectChannel $webProjectChannel): void
    {
        $rolePosition = $user->roles->position ?? null;

        switch ($rolePosition) {
            case 'operations_supervisor':
                if ($webProjectChannel->sub_status == 'Site Map') {
                    $webProjectChannel->update(['status' => 'Submitted to Client']);
                    $webProjectChannel->web_job_orders->update(['supervisor_signed_draft_id' => $user->id]);
                    $webProjectChannel->web_job_orders->update(['client_signed_id' => WebProject::where('id', $webProjectChannel->project_id)->first()->client_id]);
                    $this->handleClientApproval($webProjectChannel, $user);
                    $webProjectChannel->update(['status' => 'Completed']);
                } else {
                    $webProjectChannel->update(['status' => 'Submitted to Client']);
                }
                break;

            case 'top_management':
                $webProjectChannel->status = 'Submitted to Client';
                break;

            case 'client':
                $this->handleClientApproval($webProjectChannel, $user);
                break;

            case 'assistant_supervisor':
                $webProjectChannel->status = 'Submitted to Operations Supervisor';
                $webProjectChannel->web_job_orders->update(['operation_signed_draft_id' => $user->id]);
                break;

            default:
                throw new \Exception('Invalid role for approval.');
        }
    }

    private function handleClientApproval(WebProjectChannel $webProjectChannel, User $user): void
    {
        $subStatus = $webProjectChannel->sub_status;
        $type = $webProjectChannel->type;
        if ($type === 'web_designer' && $webProjectChannel->where('type', 'web_designer')->where('status', 'Submitted to Client')->exists()) {
            if ($subStatus === 'Site Map') {
                $this->completeAndCreateNext($webProjectChannel, $user, 'Draft Homepage Approval');
            } elseif ($subStatus === 'Draft Homepage Approval') {
                $this->completeAndCreateNext($webProjectChannel, $user, 'Final Homepage Approval');
            } elseif ($subStatus === 'Final Homepage Approval') {
                $this->completeAndCreateNext($webProjectChannel, $user, 'All Pages Approval');
            } elseif ($subStatus === 'All Pages Approval') {
                $this->completeAndFinish($webProjectChannel, $user);
                $this->startFrontEnd($webProjectChannel);
            }
        } elseif ($type === 'front_end' && $webProjectChannel->where('type', 'front_end')->where('status', 'Submitted to Client')->exists()) {
            $this->completeAndFinish($webProjectChannel, $user);
            $this->startBackEnd($webProjectChannel);
        } elseif ($type === 'back_end' && $webProjectChannel->where('type', 'back_end')->where('status', 'Submitted to Client')->exists()) {
            $this->completeAndFinish($webProjectChannel, $user);
        }
    }

    private function completeAndCreateNext(WebProjectChannel $webProjectChannel, User $user, string $nextSubStatus): void
    {
        $this->completeAndFinish($webProjectChannel, $user);

        $newJobOrder = WebJobOrder::create(['status' => 'Job order for ' . User::find($webProjectChannel->user_id)->name]);

        WebProjectChannel::create([
            'user_id' => $webProjectChannel->user_id,
            'web_job_order_id' => $newJobOrder->id,
            'project_id' => $webProjectChannel->project_id,
            'status' => 'pending',
            'sub_status' => $nextSubStatus,
            'type' => $webProjectChannel->type,
            'date_started' => Carbon::now(),
            'date_targeted' => Carbon::now()->addDays(3),
        ]);

        $this->completeWebDesigners($webProjectChannel, $user, $nextSubStatus);
    }

    private function completeAndFinish(WebProjectChannel $webProjectChannel, User $user): void
    {
        $webProjectChannel->update(['status' => 'Completed', 'date_completed' => Carbon::now()]);
        $webProjectChannel->web_job_orders->update(['client_signed_id' => $user->id]);
    }

    private function completeWebDesigners(WebProjectChannel $webProjectChannel, User $user, string $subStatus): void
    {
        $webDesigners = $webProjectChannel->where('type', 'web_designer')
            ->where('sub_status', 'like', "%{$subStatus}%")
            ->whereNotIn('status', ['declined', 'pending', 'completed'])
            ->get();

        foreach ($webDesigners as $designer) {
            if ($webProjectChannel->user_id !== $designer->user_id) {
                $designer->update(['status' => 'Completed', 'date_completed' => Carbon::now()]);
                $designer->web_job_orders->update(['client_signed_id' => $user->id]);
                $newJobOrder = WebJobOrder::create(['status' => 'Job order for ' . User::find($designer->user_id)->name]);
                WebProjectChannel::create([
                    'user_id' => $designer->user_id,
                    'web_job_order_id' => $newJobOrder->id,
                    'project_id' => $designer->project_id,
                    'status' => 'pending',
                    'sub_status' => $subStatus,
                    'type' => $designer->type,
                    'date_started' => Carbon::now(),
                    'date_targeted' => Carbon::now()->addDays(3),
                ]);
            }
        }
    }

    private function startFrontEnd(WebProjectChannel $webProjectChannel): void
    {
        $frontEnds = $webProjectChannel->where('type', 'front_end')->where('status', 'pending')->get();
        foreach ($frontEnds as $frontEnd) {
            $frontEnd->update([
                'date_started' => Carbon::now(),
                'date_targeted' => Carbon::now()->addDays(3),
            ]);
        }
    }

    private function startBackEnd(WebProjectChannel $webProjectChannel): void
    {
        $backEnds = $webProjectChannel->where('type', 'back_end')->where('status', 'pending')->get();
        foreach ($backEnds as $backEnd) {
            $backEnd->update([
                'date_started' => Carbon::now(),
                'date_targeted' => Carbon::now()->addDays(3),
            ]);
        }
    }
}
