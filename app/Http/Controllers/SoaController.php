<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\RwebDetail;
use App\Models\Soa;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'job_draft_id' => 'required|exists:job_drafts,id',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company' => 'required'
        ]);

        // Get the uploaded file
        $file = $request->file('image_path');

        // Generate a unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Define the target path within the public directory
        $targetPath = public_path('soa_images/' . $fileName);

        // Move the file to the desired path
        $file->move(public_path('soa_images'), $fileName);

        // Fetch required details
        $rwebDetail = RwebDetail::first();
        $jobDraft = JobDraft::find($request->job_draft_id);

        // Save SOA
        Soa::create([
            'bill_from' => $rwebDetail->address,
            'telephone' => $rwebDetail->telephone,
            'job_draft_id' => $request->job_draft_id,
            'image_path' => 'soa_images/' . $fileName, // Save relative path in DB
            'company' => $request->company,
            'client_name' => $jobDraft->client->name,
            'address' => $jobDraft->client->address,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.smm.soa')->with('success', 'SOA Created Successfully');
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
            'status' => 'Submitted to Top Management',
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

    public function edit($id)
    {
        $job_drafts = JobDraft::with("jobOrder")->get();
        $soa = Soa::with('jobDraft', 'preparedBy', 'approvedBy', 'particulars')->find($id);
        return view('admin.smm.soa.edit', compact('soa', 'job_drafts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'job_draft_id' => 'required',
            'image_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate as an image
            'company' => 'required'
        ]);

        // Find the existing record
        $soa = Soa::find($id);

        if (!$soa) {
            return redirect()->back()->with('error', 'SOA not found');
        }

        // Check if an image was uploaded
        if ($request->hasFile('image_path')) {
            // Get the uploaded file
            $file = $request->file('image_path');

            // Generate a unique file name
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Move the file to the desired path
            $file->move(public_path('soa_images'), $fileName);

            // Set the new image path
            $imagePath = 'soa_images/' . $fileName;
        } else {
            // Keep the existing image path if no new image was uploaded
            $imagePath = $soa->image_path;
        }

        // Update the record
        $soa->update([
            'job_draft_id' => $request->job_draft_id,
            'image_path' => $imagePath, // Use the determined image path
            'company' => $request->company,
            'client_name' => JobDraft::find($request->job_draft_id)->client->name,
            'address' => JobDraft::find($request->job_draft_id)->client->address,
        ]);

        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Updated Successfully');
    }


    public function edit_particulars($id)
    {
        $soa = Soa::with('jobDraft', 'preparedBy', 'approvedBy', 'particulars')->find($id);
        return view('admin.smm.soa.edit_particulars', compact('soa'));
    }

    public function update_particulars(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'billing_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:billing_date',
            'particulars' => 'required|array',
            'particulars.*.id' => 'nullable|exists:soa_particulars,id', // Allow updating existing particulars
            'particulars.*.date' => 'required|date',
            'particulars.*.reference' => 'required|string',
            'particulars.*.quantity' => 'required|integer|min:1',
            'particulars.*.particulars' => 'required|string',
            'particulars.*.charges' => 'required|numeric|min:0',
        ]);

        // Find SOA record
        $soa = Soa::findOrFail($id);
        $soa->update([
            'billing_date' => $validatedData['billing_date'],
            'due_date' => $validatedData['due_date'],
            'prepared_by' => auth()->user()->id,
            'status' => 'Submitted to Top Management',
        ]);

        SoaParticular::where('soa_id', $soa->id)->delete();

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
        return redirect()->route('admin.smm.soa')->with('Success', 'SOA Updated Successfully');
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

    public function downloadPDF($id)
    {
        //Subject to remove
        $rweb_details = RwebDetail::with('paymentMethods')->first();
        $soa = Soa::with('jobDraft', 'preparedBy', 'approvedBy', 'particulars')->find($id);

        $pdf = Pdf::loadView('admin.smm.soa.pdf', compact('rweb_details', 'soa'));

        return $pdf->download(
            'soa' . $id . '.pdf'
        );
    }
}
