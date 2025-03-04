<?php

namespace App\Providers;

use App\Models\JobDraft;
use App\Models\Notification;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $user = Auth::user(); // Get the logged-in user

            if ($user && $user->id == 1) {
                // Admin (ID = 1) sees all notifications
                $notifications = Notification::with('users')->where('user_id', $user->id ?? 0)->get()->sortByDesc('created_at');
            } else {
                // Regular users see only their notifications
                $notifications = Notification::with('users')->where('user_id', $user->id ?? 0)->get()->sortByDesc('created_at');
            }

            //$view->with('notifications', $notifications);

            $clientDraftCount = 0;
            $supervisorDraftCount = 0;
            $authuser = auth()->user();

            if (auth()->check()) {
                $clientDraftCount = JobDraft::where('client_id', auth()->user()->id)
                    ->where('status', 'Submitted to Client')
                    ->count();

                // Adjust this query according to your supervisor logic.
                $supervisorDraftCount = Request::with('assignee')
                    ->count();

                // $supervisorDirectDraftCount = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner')
                //     ->count();

                $supervisorTaskCountContent = JobDraft::where('content_writer_id', $authuser->id)
                    ->where('type', 'content_writer')
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client']) // Ensures relations are loaded
                    ->count();

                $supervisorTaskCountGraphic = JobDraft::where('graphic_designer_id', $authuser->id)
                    ->where('type', 'graphic_designer')
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client']) // Ensures relations are loaded
                    ->count();

                $job_drafts_content = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                    ->whereHas('revisions')
                    ->where('type', 'content_writer')
                    ->where('content_writer_id', $authuser->id)
                    ->where('status', 'Revision')
                    ->get();

                $job_drafts_graphic = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                    ->whereHas('revisions')
                    ->where('type', 'graphic_designer')
                    ->where('graphic_designer_id', $authuser->id)
                    ->where('status', 'Revision')
                    ->get();

                // Merge both collections into one
                $revisionCollection = $job_drafts_content->merge($job_drafts_graphic);

                // Count total items if needed
                $revisionCount = $revisionCollection->count();

                $supervisorApprovalCount = JobDraft::where('status', 'Submitted to Supervisor')
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                    ->count();


                // ADMIN OPERATION
                $operationTaskCountContent = JobDraft::where('content_writer_id', $authuser->id)
                    ->where('type', 'content_writer')
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client']) // Ensures relations are loaded
                    ->count();

                $operationTaskCountGraphic = JobDraft::where('graphic_designer_id', $authuser->id)
                    ->where('type', 'graphic_designer')
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client']) // Ensures relations are loaded
                    ->count();

                $operationIncomingRequestCount = Request::where('assigned_to', auth()->user()->id)
                    ->whereDoesntHave('jobOrders') // Exclude requests already assigned to JobOrders
                    ->count();

                $operationApprovalCount = JobDraft::where('status', 'Submitted to Operations')
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                    ->count();

                $operationRevisionCount = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client', 'revisions'])
                    ->where('status', 'Revision')
                    ->where(function ($query) {
                        $query->where('content_writer_id', auth()->user()->id)
                            ->orWhere('graphic_designer_id', auth()->user()->id);
                    })
                    ->count(); // Retrieve all records


                // Content Writer

                $contentDraftCount = JobDraft::where('content_writer_id', $authuser->id)
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->where('type', 'content_writer')
                    ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
                    ->count();

                $contentRevisionCount = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                    ->whereHas('revisions')
                    ->where('status', 'revision')
                    ->where('type', 'content_writer')
                    ->where('content_writer_id', $authuser->id)
                    ->count();

                $graphicDraftCount = JobDraft::where('graphic_designer_id', $authuser->id)
                    ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                    ->where('type', 'graphic_designer')
                    ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
                    ->count();

                $graphicRevisionCount = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client')
                    ->whereHas('revisions')
                    ->where('type', 'graphic_designer')
                    ->where('graphic_designer_id', $authuser->id)
                    ->count();

                $topmanagerApprovalCount = JobDraft::where('status', 'Submitted to Top Manager')
                    ->with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                    ->count();

                $view->with(compact('clientDraftCount', 'supervisorDraftCount', 'supervisorTaskCountContent', 'supervisorTaskCountGraphic', 'operationTaskCountGraphic', 'supervisorApprovalCount', 'operationTaskCountContent', 'operationIncomingRequestCount', 'operationApprovalCount', 'operationRevisionCount', 'contentDraftCount', 'contentRevisionCount', 'graphicDraftCount', 'graphicRevisionCount', 'topmanagerApprovalCount', 'revisionCount'))->with('notifications', $notifications);
            }

        });
    }
}