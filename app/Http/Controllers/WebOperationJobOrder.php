<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\WebRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebOperationJobOrder extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_requests = WebRequest::with('issued_to')->get();
        return view('admin.web-development.operation-job-order.index', compact('web_requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::all();
        $operatorRole = Role::where('position', 'like', '%assistant_supervisor%')->first();

        if (!$operatorRole) {
            // Handle the case where the "assistant_supervisor" role is not found.
            // You might want to log an error, display a message, or redirect.
            // Example:
            // Log::error('Assistant supervisor role not found.');
            // return redirect()->back()->with('error', 'Assistant supervisor role not found.');
            $operators = collect(); // Return an empty collection to avoid errors later on.
        } else {
            $operators = User::where('role_id', $operatorRole->id)->get();
        }

        // Remove the @dd($operators) line for production. It's for debugging.
        // dd($operators);

        return view('admin.web-development.operation-job-order.create', compact('employees', 'operators'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();

            $request->validate([
                'title' => 'required',
                'client' => 'nullable',
                'deadline' => 'required',
                'instructions' => 'required',
                'assigned_to' => 'required',
            ]);

            WebRequest::create([
                'title' => $request->title,
                'instructions' => $request->instructions,
                'assigned_to' => $request->assigned_to,
                'assigned_by' => auth()->user()->id,
                'status' => 'pending',
                'deadline' => $request->deadline
            ]);

            DB::commit();

            $web_requests = WebRequest::with('issued_to')->get();

            return view('admin.web-development.operation-job-order.index', compact('web_requests'));
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $web_request = WebRequest::with('issued_to', 'issued_by')->find($id);
        return view('admin.web-development.operation-job-order.show', compact('web_request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            DB::beginTransaction();

            $operatorRole = Role::where('position', 'like', '%assistant_supervisor%')->first();

            if (!$operatorRole) {
                // Handle the case where the "assistant_supervisor" role is not found.
                // You might want to log an error, display a message, or redirect.
                // Example:
                // Log::error('Assistant supervisor role not found.');
                // return redirect()->back()->with('error', 'Assistant supervisor role not found.');
                $operators = collect(); // Return an empty collection to avoid errors later on.
            } else {
                $operators = User::where('role_id', $operatorRole->id)->get();
            }

            $webRequest = WebRequest::find($id);

            if (!$webRequest) {
                DB::rollBack(); // Rollback the transaction if no operations are found
                return back()->with('Status', 'webRequest not found.');
            }

            DB::commit();

            return view('admin.web-development.operation-job-order.edit', compact('operators', 'webRequest'));
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
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
        try {
            DB::beginTransaction();
            $request->validate([
                'title' => 'required',
                'client' => 'nullable',
                'deadline' => 'required',
                'instructions' => 'required',
                'assigned_to' => 'required',
            ]);

            $web_request = WebRequest::find($id);

            if (!$web_request) {
                DB::rollBack(); // Rollback the transaction if no operations are found
                return back()->with('Status', 'Web Request not found.');
            }

            $web_request->title = $request->title;
            $web_request->instructions = $request->instructions;
            $web_request->assigned_to = $request->assigned_to;
            $web_request->deadline = $request->deadline;
            $web_request->save();

            DB::commit();

            return redirect()->route('admin.web.operation-job-order')->with('Status', 'Web Request successfully updated.');
        } catch (Exception $ex) {
            @dd($ex->getMessage());
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $web_request = WebRequest::find($id);

            if (!$web_request) {
                return back()->with('Status', 'Web Request not found.');
            }

            $web_request->delete();

            DB::commit();

            return back()->with('Status', 'Web Request successfully deleted.');
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('Status', $ex->getMessage());
        }
    }
}
