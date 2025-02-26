<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/admin/front-end/dashboard', 'admin.front-end.dashboard')->name('admin.front-end.dashboard');
Route::view('/admin/front-end/project-development', 'admin.front-end.project-development')->name('admin.front-end.project-development');