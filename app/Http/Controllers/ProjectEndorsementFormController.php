<?php

namespace App\Http\Controllers;

use App\Models\ProjectEndorsementForm;
use App\Models\User;
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
        $users = User::all();
        return view('admin.smm.endorsement.create', compact('users'));
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
     * @param  \App\Models\ProjectEndorsementForm  $projectEndorsementForm
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectEndorsementForm $projectEndorsementForm)
    {
        //
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
}
