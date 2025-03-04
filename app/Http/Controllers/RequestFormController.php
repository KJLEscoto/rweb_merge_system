<?php

namespace App\Http\Controllers;

use App\Models\Particular;
use App\Models\RequestForm;
use App\Models\User;
use Illuminate\Http\Request;

class RequestFormController extends Controller
{
    public function history()
    {
        $authuser = auth()->user();
        $request_forms = collect(); // Empty collection to avoid errors

        if ($authuser->role_id == '5') {
            $request_forms = RequestForm::with('requestedBy', 'manager', 'receiver', 'particulars')
                ->whereIn('manager_id', [$authuser->id, null])
                ->get();
        } elseif ($authuser->role_id == '7') {
            $request_forms = RequestForm::with('requestedBy', 'manager', 'receiver', 'particulars')
                ->whereIn('receiver_id', [$authuser->id, null])
                ->get();
        } elseif ($authuser->role_id == '6') {
            $request_forms = RequestForm::with('requestedBy', 'manager', 'receiver', 'particulars')
                ->get(); // Changed from all() to get()
        }

        return view('pages.requestForm.history', compact('request_forms'));
    }

    public function show($id)
    {
        $request_form = RequestForm::with(['requestedBy', 'manager', 'receiver', 'particulars'])->findOrFail($id);

        return view('pages.requestForm.show', compact('request_form'));
    }

    public function edit($id)
    {
        $users = User::all();
        $managers = User::where('role_id', 5)->get();
        $accounting = User::where('role_id', 7)->get();
        $request_form = RequestForm::with(['requestedBy', 'manager', 'receiver', 'particulars'])->findOrFail($id);

        return view('pages.requestForm.edit', compact('request_form', 'users', 'managers', 'accounting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required'
        ]);

        $request_form = RequestForm::findOrFail($id);

        $request_form->update([
            'department' => auth()->user()->role->position,
            'date' => $request->date,
            'description' => $request->description,
            'requested_by' => auth()->user()->id,
            'manager_id' => $request->manager_id,
            'receiver_id' => $request->receiver_id,
            'status' => 'Approved by Operation'
        ]);

        // Update particulars: delete existing ones and insert new ones
        $request_form->particulars()->delete();

        foreach ($request->particulars as $particular) {
            Particular::create([
                'request_form_id' => $request_form->id,
                'particular' => $particular
            ]);
        }

        return redirect()->route('requestForm')->with('Status', 'Request Form Updated Successfully.');
    }


    public function create()
    {
        $users = User::all();
        $managers = User::where('role_id', 5)->get();
        $accounting = User::where('role_id', 7)->get();

        // Get the latest request form
        $request_form = RequestForm::with(['requestedBy', 'manager', 'receiver', 'particulars'])
            ->latest()
            ->first();

        return view('pages.requestForm.create', compact('users', 'managers', 'accounting', 'request_form'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required'
        ]);

        $request_form = RequestForm::create([
            'department' => auth()->user()->role->position,
            'date' => $request->date,
            'description' => $request->description,
            'requested_by' => auth()->user()->id,
            'manager_id' => $request->manager_id,
            'receiver_id' => $request->receiver_id,
            'status' => 'Approved by Operation'
        ]);

        // Loop through the particulars and insert them
        foreach ($request->particulars as $particular) {
            Particular::create([
                'request_form_id' => $request_form->id,
                'particular' => $particular
            ]);
        }

        return redirect()->route('requestForm')->with('Status', 'Request Form Created Successfully.');
    }

    public function approve($id)
    {
        $request_form = RequestForm::find($id);
        $authuser = auth()->user();

        // Determine the status based on the role
        $status = null;
        if ($authuser->role_id == 5) {
            $status = 'Approved by Top Manager';
        } elseif ($authuser->role_id == 7) {
            $status = 'Approved by Accounting';
        }

        $request_form->update([
            'manager_id' => $authuser->id,
            'status' => $status,
        ]);

        return redirect()->route('requestForm.history')->with('Status', 'Request Form Approve Successfully.');
    }

    public function delete($id)
    {
        $request_form = RequestForm::findOrFail($id);

        // Delete related particulars first to avoid foreign key constraint issues
        $request_form->particulars()->delete();

        // Delete the request form
        $request_form->delete();

        return redirect()->route('requestForm.history')->with('Status', 'Request Form Deleted Successfully.');
    }
}
