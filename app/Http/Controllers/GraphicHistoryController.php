<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GraphicHistoryController extends Controller
{
    public function index()
    {
        $authuser = auth()->user();

        // Fetch all job drafts for the authenticated user
        $job_drafts = JobDraft::where('graphic_designer_id', $authuser->id)
            ->where('type', 'graphic_designer')
            ->with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->get();
        return view('pages.graphic_designer.history.index', compact('job_drafts'));
    }
    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('pages.graphic_designer.history.show', compact('job_draft'));
    }
    public function downloadPDF($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        $pdf = Pdf::loadView('pages.graphic_designer.history.show', compact('job_draft'));

        return $pdf->download('job_order_' . $id . '.pdf');
    }
}
