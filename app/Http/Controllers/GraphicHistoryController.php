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
        return view('admin.smm.graphic_designer.history.index', compact('job_drafts'));
    }
    public function show($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);
        return view('admin.smm.graphic_designer.history.show', compact('job_draft'));
    }
    public function downloadPDF($id)
    {
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        $pdf = Pdf::loadView('admin.smm.graphic_designer.history.show', compact('job_draft'));

        if($job_draft->type === "content_writer"){
            $worker = $job_draft->contentWriter->name;
        }else{
            $worker = $job_draft->graphicDesigner->name;
        }

        return $pdf->download(
            str_replace(' ', '', $job_draft->type . '-' . $worker . '-' . $job_draft->client->name . '-' . $job_draft->date_started) . '.pdf'
        );  
    }
}
