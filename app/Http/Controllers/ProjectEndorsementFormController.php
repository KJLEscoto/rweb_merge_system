<?php

namespace App\Http\Controllers;

use App\Models\ProjectEndorsementForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectEndorsementFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $endorsements = ProjectEndorsementForm::all();
        return view('admin.smm.endorsement.index', compact('endorsements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = User::where('role_id', 1)->get();
        $users = User::all();
        return view('admin.smm.endorsement.create', compact('users', 'clients'));
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
            'title' => 'required',
            'client_id' => 'required',
            'person_in_charge' => 'required',
            'project_scope' => 'required',
            'timeline' => 'required',
            'deliverables' => 'required',
        ]);



        ProjectEndorsementForm::create([
            'title' => $request->title,
            'client_id' => $request->client_id,
            'date_issued' => Carbon::today(), // Ensures only the date is stored
            'person_in_charge' => $request->person_in_charge,
            'issued_by' => auth()->user()->id,
            'project_scope' => $request->project_scope,
            'timeline' => $request->timeline,
            'deliverables' => $request->deliverables,
            'prepared_by' => auth()->user()->id,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.smm.endorsement')->with('success', 'Project Endorsement Form Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectEndorsementForm  $projectEndorsementForm
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $endorsement = ProjectEndorsementForm::with('client', 'notedBy', 'personInCharge', 'approvedBy')->find($id);
        return view('admin.smm.endorsement.show', compact('endorsement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectEndorsementForm  $projectEndorsementForm
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectEndorsementForm $projectEndorsementForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectEndorsementForm  $projectEndorsementForm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectEndorsementForm $projectEndorsementForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectEndorsementForm  $projectEndorsementForm
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectEndorsementForm $projectEndorsementForm)
    {
        //
    }

    public function approval()
    {
        $endorsements = ProjectEndorsementForm::all();
        return view('admin.smm.endorsement.approval', compact('endorsements'));
    }
}
