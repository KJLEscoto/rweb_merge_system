<?php

namespace App\Http\Controllers;

use App\Models\WebRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_requests = WebRequest::with('issued_to', 'issued_by')->where('assigned_to', auth()->user()->id)->get();
        return view('admin.web-development.incoming-requests.index', compact('web_requests'));
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
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        @dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(WebRequest $webRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebRequest $webRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebRequest  $webRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebRequest $webRequest)
    {
        //
    }

    public function accept($id)
    {
        WebRequest::find($id)->update([
            'status' => 'accepted',
            'date_accepted' => Carbon::now(),
        ]);
        return redirect()->route('admin.web.incoming-requests');
    }
}
