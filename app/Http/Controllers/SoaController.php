<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\RwebDetail;
use App\Models\Soa;
use App\Models\SoaParticular;
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

        $request->validate([
            'job_draft_id' => 'required',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate as an image
            'company' => 'required'
        ]);

        // Store the file in public/soa_images and get the file path
        $imagePath = $request->file('image_path')->store('soa_images', 'public');

        Soa::create([
            'bill_from' => RwebDetail::first()->address,
            'telephone' => RwebDetail::first()->telephone,
            'job_draft_id' => $request->job_draft_id,
            'image_path' => $imagePath, // Save the stored path in DB
            'company' => $request->company,
            'client_name' => JobDraft::find($request->job_draft_id)->client->name,
            'address' => JobDraft::find($request->job_draft_id)->client->address,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Created Successfully');
    }

    public function create_particulars($id)
    {
        $soa = Soa::with('jobDraft', 'preparedBy', 'approvedBy', 'particulars')->find($id);
        return view('admin.smm.soa.particulars.create', compact('soa'));
    }

    public function store_particulars(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:billing_date',
            'particulars' => 'required|array',
            'particulars.*.date' => 'required|date',
            'particulars.*.reference' => 'required|string',
            'particulars.*.quantity' => 'required|integer|min:1',
            'particulars.*.particulars' => 'required|string', // Fixing the name to match request
            'particulars.*.charges' => 'required|numeric|min:0',
        ]);

        // Update SOA record
        $soa = Soa::findOrFail($id);
        $soa->update([
            'billing_date' => $validatedData['billing_date'],
            'due_date' => $validatedData['due_date'],
            'prepared_by' => auth()->user()->id
        ]);

        // Store multiple SOA Particulars
        foreach ($validatedData['particulars'] as $particular) {
            SoaParticular::create([
                'soa_id' => $soa->id,
                'date' => $particular['date'],
                'reference' => $soa->job_draft_id,
                'quantity' => $particular['quantity'],
                'particulars' => $particular['particulars'], // Use correct key
                'charges' => $particular['charges'],
            ]);
        }

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Created Successfully');
    }




    public function show($id)
    {
        $rweb_details = RwebDetail::with('paymentMethods')->first();
        $soa = Soa::with('jobDraft', 'preparedBy', 'approvedBy', 'particulars')->find($id);
        return view('admin.smm.soa.show', compact('soa', 'rweb_details'));
    }

    public function destroy($id)
    {
        $soa = Soa::find($id);
        $soa->delete();
        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Deleted Successfully');
    }

    public function approve($id)
    {
        Soa::find($id)->update([
            'status' => 'Approved by Top Management',
            'approved_by' => auth()->user()->id
        ]);

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Approved Successfully');
    }
}
