<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SoaController extends Controller
{
    public function index()
    {
        return view('admin.smm.soa.index');
    }

    public function create()
    {
        return view('admin.smm.soa.create');
    }
}
