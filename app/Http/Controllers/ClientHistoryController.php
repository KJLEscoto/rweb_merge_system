<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ClientHistoryController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where('client_id', $authuser->id)
            ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->get();
        return view('pages.client.history.index', compact('job_drafts'));
    }
    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.client.history.show', compact('job_draft'));
    }
    public function downloadPDF($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        $pdf = Pdf::loadView('pages.client.history.show', compact('job_draft'));

        return $pdf->download('job_order_' . $id . '.pdf');
    }
}
