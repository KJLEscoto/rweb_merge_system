<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MergeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function mergeLogin(Request $request)
    {
        /**
         * Ang gamit ini na controller is to pasa ang data sa other controller
         * para no need na mag input sa login napud sa pikas system
         */
        return null;
    }

    public function mergeRegister(Request $request)
    {
        /**
         * Ang gamit ini na controller is to pasa ang data sa other controller
         * para no need na mag input sa login napud sa pikas system
         */
        return null;
    }

    public function mergeEdit(Request $request)
    {
        /**
         * Ang gamit ini na controller is to pasa ang data sa other controller
         * para no need na mag input sa login napud sa pikas system
         */
        return null;
    }
}
