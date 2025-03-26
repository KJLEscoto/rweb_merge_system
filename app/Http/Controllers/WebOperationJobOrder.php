<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WebRequest;
use Illuminate\Http\Request;

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
        return view('admin.web-development.operation-job-order.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'client' => 'required',
            'deadline' => 'required',
            'instructions' => 'required'
        ]);

        WebRequest::create([
            'title' => $request->title,
            'instructions' => $request->instructions,
            'assigned_to' => $request->client,
            'assigned_by' => auth()->user()->id,
            'status' => 'pending',
            'deadline' => $request->deadline
        ]);

        $web_requests = WebRequest::with('issued_to')->get();
        return view('admin.web-development.operation-job-order.index', compact('web_requests'));
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
}
