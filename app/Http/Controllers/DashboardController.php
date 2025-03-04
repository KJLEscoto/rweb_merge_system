<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $user_role = $user->role->id;

        if ($user_role == 1) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('client_id', $user->id)
                ->whereIn('status', ['Submitted to Client', 'completed'])
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();

            return view('dashboard', compact('job_drafts'));
        } elseif ($user_role == 2) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->whereNotIn('status', ["pending", "Revision", 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                ->orderBy('id', 'desc') // Sort by id descending
                ->limit(5) // Ensure a maximum of 5 records
                ->get();

            $job_drafts_revisions_content = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('type', 'content_writer')
                ->where('content_writer_id', auth()->user()->id) // <-- Fixed parentheses
                ->where('status', 'Revision')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $job_drafts_revisions_graphic = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('type', 'graphic_designer')
                ->where('graphic_designer_id', auth()->user()->id) // <-- Fixed parentheses
                ->where('status', 'Revision')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $job_drafts_revisions = $job_drafts_revisions_graphic->merge($job_drafts_revisions_content); // Formatting fix

            $my_tasks = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where(function ($query) use ($user) {
                    $query->where('content_writer_id', $user->id)
                        ->orWhere('graphic_designer_id', $user->id);
                })
                ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                ->orderBy('id', 'desc')
                ->take(5) // Eloquent's alternative to limit()
                ->get();

            return view('dashboard', compact('job_drafts', 'job_drafts_revisions', 'my_tasks')); // Include both variables
        } elseif ($user_role == 3) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->whereNot('status', 'Revision')
                ->where('content_writer_id', $user->id)
                ->where('type', 'content_writer')
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();

            $job_drafts_revisions = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('content_writer_id', $user->id)
                ->where('type', 'content_writer')
                ->where('status', 'Revision')
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();
            return view('dashboard', compact('job_drafts', 'job_drafts_revisions'));
        } elseif ($user_role == 4) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->whereNot('status', 'Revision')
                ->where('graphic_designer_id', $user->id)
                ->where('type', 'graphic_designer')
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();

            $job_drafts_revisions = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('graphic_designer_id', $user->id)
                ->where('type', 'graphic_designer')
                ->where('status', 'Revision')
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();

            return view('dashboard', compact('job_drafts', 'job_drafts_revisions'));
        } elseif ($user_role == 5) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->whereIn('status', ['Submitted to Client', 'completed', 'Submitted to Top Manager'])
                ->orderBy('id', 'desc') // Sort by id descending
                ->take(5) // Get the latest 5 data
                ->get();

            return view('dashboard', compact('job_drafts'));
        } elseif ($user_role == 6) {
            $job_drafts = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->whereNotIn('status', ['pending', 'Submitted to Operations', 'Revision', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                ->orderBy('id', 'desc') // Sort by id descending
                ->limit(5) // Use limit for consistency
                ->get();

            $job_drafts_revisions_content = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('type', 'content_writer')
                ->where('content_writer_id', auth()->user()->id) // <-- Fixed parentheses
                ->where('status', 'Revision')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $job_drafts_revisions_graphic = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where('type', 'graphic_designer')
                ->where('graphic_designer_id', auth()->user()->id) // <-- Fixed parentheses
                ->where('status', 'Revision')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            $job_drafts_revisions = $job_drafts_revisions_graphic->merge($job_drafts_revisions_content); // Formatting fix

            $my_tasks = JobDraft::with(['jobOrder', 'contentWriter', 'graphicDesigner', 'client'])
                ->where(function ($query) use ($user) {
                    $query->where('content_writer_id', $user->id)
                        ->orWhere('graphic_designer_id', $user->id);
                })
                ->whereIn('status', ['pending', 'Waiting for Content Writer Approval', 'Waiting for Graphic Designer Approval'])
                ->orderBy('id', 'desc')
                ->take(5) // Eloquent's alternative to limit()
                ->get();


            return view('dashboard', compact('job_drafts', 'job_drafts_revisions', 'my_tasks')); // Include both variables
        } elseif ($user_role == 7) {
            $job_drafts = [];
            return view('dashboard', compact('job_drafts')); // Include both variables
        }
    }
}
