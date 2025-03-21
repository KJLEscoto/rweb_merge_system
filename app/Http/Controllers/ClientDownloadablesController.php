<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ClientDownloadablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authuser = auth()->user();

        $job_drafts = JobDraft::with('jobOrder', 'contentWriter', 'graphicDesigner', 'client') // Corrected ->with() usage
            ->whereNotNull('client_signature')
            ->get();
        return view('admin.smm.client_downloadables.index', compact('job_drafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        //
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

    public function downloadPDF($id)
    {
        //Subject to remove
        $job_draft = JobDraft::with('jobOrder.issuer', 'contentWriter', 'graphicDesigner', 'client')->find($id);

        $pdf = Pdf::loadView('admin.smm.client_downloadables.show', compact('job_draft'));

        if ($job_draft->type === "content_writer") {
            $worker = $job_draft->contentWriter->name;
        } else {
            $worker = $job_draft->graphicDesigner->name;
        }

        return $pdf->download(
            str_replace(' ', '', $job_draft->type . '-' . $worker . '-' . $job_draft->client->name . '-' . $job_draft->date_started) . '.pdf'
        );
    }
}
