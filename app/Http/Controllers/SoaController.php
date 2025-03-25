<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\RwebDetail;
use App\Models\Soa;
use Illuminate\Http\Request;

class SoaController extends Controller
{
    public function index()
    {
        $soas = Soa::with('jobDraft')->get();
        return view('admin.smm.soa.index', compact('soas'));
    }

    public function create()
    {
        $job_drafts = JobDraft::with("jobOrder")->get();
        return view('admin.smm.soa.create', compact('job_drafts'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'job_draft_id' => 'required',
            'image_path' => 'required',
            'company' => 'required'
        ]);

        Soa::create([
            'bill_from' => RwebDetail::first()->address,
            'telephone' => RwebDetail::first()->telephone,
            'job_draft_id' => $request->job_draft_id,
            'image_path' => $request->image_path,
            'company' => $request->company,
            'client_name' => JobDraft::find($request->job_draft_id)->client->name,
            'address' => JobDraft::find($request->job_draft_id)->client->address,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Created Successfully');
    }

    public function create_particulars($id)
    {
        $soa = Soa::find($id);
        return view('admin.smm.soa.create_particulars', compact('soa'));
    }

    public function store_particulars(Request $request, $id)
    {
        $request->validate([
            'job_draft_id' => 'required',
            'image_path' => 'required',
            'company' => 'required'
        ]);

        Soa::update([
            'date' => $request->date,

        ]);

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Created Successfully');
    }
}
