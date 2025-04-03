<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebApprovalController;
use App\Http\Controllers\WebAuthenticatedSessionController;
use App\Http\Controllers\WebDirectJobOrderController;
use App\Http\Controllers\WebOperationJobOrder;
use App\Http\Controllers\WebRequestController;
use App\Http\Controllers\WebRevisionController;
use App\Http\Controllers\WebTaskController;
use App\Http\Controllers\WebTrackController;
use App\Http\Controllers\WebUserController;
use App\Models\Page;
use App\Models\Privilege;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


// web development routes
//pages

if (Schema::hasTable('pages') && Schema::hasTable('privileges')) {
  $dashboard = optional(Page::where('description', 'like', '%dashboard%')->first())->id;
  $direct_job_order = optional(Page::where('description', 'like', '%direct_job_order%')->first())->id;
  $operation_job_order = optional(Page::where('description', 'like', '%operation_job_order%')->first())->id;
  $task = optional(Page::where('description', 'like', '%task%')->first())->id;
  $revision = optional(Page::where('description', 'like', '%revision%')->first())->id;
  $approvals = optional(Page::where('description', 'like', '%approvals%')->first())->id;
  $track = optional(Page::where('description', 'like', '%track%')->first())->id;
  $users = optional(Page::where('description', 'like', '%users%')->first())->id;
  $downloadables = optional(Page::where('description', 'like', '%downloadables%')->first())->id;
  $profile = optional(Page::where('description', 'like', '%profile%')->first())->id;

  // Privileges
  $can_read = optional(Privilege::where('description', 'like', '%can_read%')->first())->id;
  $can_update = optional(Privilege::where('description', 'like', '%can_update%')->first())->id;
  $can_create = optional(Privilege::where('description', 'like', '%can_create%')->first())->id;
  $can_delete = optional(Privilege::where('description', 'like', '%can_delete%')->first())->id;
} else {
  // Handle the case where the tables do not exist
  return response()->json(['error' => 'Required tables do not exist in the system_merge database.'], 500);
}

Route::get('web/login', [WebAuthenticatedSessionController::class, 'create'])->name('web.login');
Route::post('web/login', [WebAuthenticatedSessionController::class, 'store'])->name('web.login.store');

//web development routes
Route::prefix('/admin/web-development')->group(function () use ($dashboard, $direct_job_order, $operation_job_order, $task, $revision, $approvals, $track, $users, $downloadables, $profile, $can_read, $can_update, $can_create, $can_delete,) {
  // Route::get('/supervisor/directjob', [Dashboard::class, 'index'])
  // Route::prefix('dashboard')->group(function () use ($dashboard, $can_read, $can_update, $can_create, $can_delete) {
  //     Route::get('/', function () {
  //         @dd('success!');
  //     })->middleware("role_channel:$dashboard, $can_read");
  // });

  Route::get('/dashboard', function () {
    return view('admin.web-development.dashboard');
  })->name('admin.web.dashboard');

  // Route::get('/users', [WebUserController::class, 'index'])->name('admin.web.users');

  // views only
  // direct job order pages
  Route::get('/direct-job-order', [WebDirectJobOrderController::class, 'index'])->name('admin.web.direct-job-order');
  Route::get('/direct-job-order/create', [WebDirectJobOrderController::class, 'create'])->name('admin.web.direct-job-order.create');
  Route::post('/direct-job-order/store', [WebDirectJobOrderController::class, 'store'])->name('admin.web.direct-job-order.store');
  Route::get('/direct-job-order/{id}/show', [WebDirectJobOrderController::class, 'show'])->name('admin.web.direct-job-order.show');
  Route::get('/direct-job-order/{id}/showProjectChannels', [WebDirectJobOrderController::class, 'showProjectChannels'])->name('admin.web.direct-job-order.showProjectChannels');
  Route::get('/direct-job-order/{id}/edit', [WebDirectJobOrderController::class, 'edit'])->name('admin.web.direct-job-order.edit');
  Route::put('/direct-job-order/{id}/edit', [WebDirectJobOrderController::class, 'editProjectChannels'])->name('admin.web.direct-job-order.edit.post');
  Route::post('/direct-job-order/{id}/delete', [WebDirectJobOrderController::class, 'destroy'])->name('admin.web.direct-job-order.delete');
  Route::get('/direct-job-order/track/{project_id}/draft/{project_channel_id}/user/{user_id}/edit', [WebtrackController::class, 'showEditDraft'])->name('admin.web.direct-job-order.draft.edit');
  Route::get('/direct-job-order/track/{id}', [WebTrackController::class, 'show'])->name('admin.web.direct-job-order.track.show');
  // Route::view('/direct-job-order/{id}/edit', 'admin.web-development.direct-job-order.edit')->name('admin.web.direct-job-order.edit');

  // operation job order pages
  Route::get('/operation-job-order', [WebOperationJobOrder::class, 'index'])->name('admin.web.operation-job-order');
  Route::get('/operation-job-order/create', [WebOperationJobOrder::class, 'create'])->name('admin.web.operation-job-order.create');
  Route::post('/operation-job-order/store', [WebOperationJobOrder::class, 'store'])->name('admin.web.operation-job-order.store');
  // Route::view('/operation-job-order', 'admin.web-development.operation-job-order.index')->name('admin.web.operation-job-order');
  // Route::view('/operation-job-order/create', 'admin.web-development.operation-job-order.create')->name('admin.web.operation-job-order.create');
  Route::get('/operation-job-order/{id}/edit', [WebOperationJobOrder::class, 'edit'])->name('admin.web.operation-job-order.edit');
  Route::put('/operation-job-order/{id}/edit/post', [WebOperationJobOrder::class, 'update'])->name('admin.web.operation-job-order.edit.post');
  Route::get('/operation-job-order/{id}/show', [WebOperationJobOrder::class, 'show'])->name('admin.web.operation-job-order.show');
  Route::delete('/operation-job-order/{id}/destroy', [WebOperationJobOrder::class, 'destroy'])->name('admin.web.operation-job-order.destroy');

  // task pages
  Route::get('/task', [WebTaskController::class, 'index'])->name('admin.web.task');
  Route::put('/task/accept/{id}', [WebTaskController::class, 'accept'])->name('admin.web.task.accept');
  Route::get('/task/create/{id}', [WebTaskController::class, 'create'])->name('admin.web.task.create');
  Route::put('/task/store/{id}', [WebTaskController::class, 'store'])->name('admin.web.task.store');
  // Route::view('/task/create/{id}', 'admin.web-development.task.create')->name('admin.web.task.create');

  // revision pages
  // Route::view('/revision', 'admin.web-development.revision.index')->name('admin.web.revision');

  Route::get('/revision', [WebRevisionController::class, 'index'])->name('admin.web.revision');
  Route::get('/revision/{id}/show', [WebRevisionController::class, 'show'])->name('admin.web.revision.show');
  Route::put('/revision/{id}/edit', [WebRevisionController::class, 'edit'])->name('admin.web.revision.edit');


  // approvals pages
  Route::get('/approvals', [WebApprovalController::class, 'index'])->name('admin.web.approvals');
  Route::get('/approvals/{id}', [WebApprovalController::class, 'show'])->name('admin.web.approvals.show');
  Route::post('/approvals/{id}/approve', [WebApprovalController::class, 'approve'])->name('admin.web.approvals.approve');
  Route::post('/approvals/{id}/decline', [WebApprovalController::class, 'decline'])->name('admin.web.approvals.decline');
  // Route::view('/approvals', 'admin.web-development.approvals.index')->name('admin.web.approvals');

  // users pages
  // Route::view('/users', 'admin.web-development.users.index')->name('admin.web.users');
  Route::get('/users', [WebUserController::class, 'index'])->name('admin.web.users');
  Route::delete('/users/{id}/destroy', [WebUserController::class, 'destroy'])->name('admin.web.users.destroy');
  Route::get('/users/create', [WebUserController::class, 'create'])->name('admin.web.users.create');
  Route::post('/users/store', [WebUserController::class, 'store'])->name('admin.web.users.store');
  Route::get('/users/{id}', [WebUserController::class, 'show'])->name('admin.web.users.show');
  Route::get('/users/edit/{id}', [WebUserController::class, 'edit'])->name('admin.web.users.edit');
  Route::put('/users/edit/{id}/update', [WebUserController::class, 'update'])->name('admin.web.users.edit.post');

  // track pages
  Route::get('/track', [WebTrackController::class, 'index'])->name('admin.web.track');
  Route::get('/track/{id}', [WebTrackController::class, 'show'])->name('admin.web.track.show');
  Route::get('/track/{track_id}/draft/{draft_id}', [WebtrackController::class, 'showDraft'])->name('admin.web.track.draft.show');
  Route::get('/track/{project_id}/draft/{project_channel_id}/user/{user_id}/edit', [WebtrackController::class, 'showEditDraft'])->name('admin.web.track.draft.edit');
  Route::post('/track/{project_id}/draft/{project_channel_id}/user/{user_id}/edit', [WebtrackController::class, 'editDraft'])->name('admin.web.track.draft.edit.post');
  Route::put('/track/{project_id}/draft/{project_channel_id}/edit', [WebtrackController::class, 'editDraft'])->name('admin.web.track.draft.edit.post');

  // downloadables pages
  Route::view('/downloadables', 'admin.web-development.downloadables.index')->name('admin.web.downloadables');

  // instructions manual pages
  Route::view('/instructions-manual', 'admin.web-development.instructions-manual.index')->name('admin.web.instructions-manual');

  // incoming request pages
  Route::get('/incoming-requests', [WebRequestController::class, 'index'])->name('admin.web.incoming-requests');
  Route::put('/incoming-requests/{id}/accept', [WebRequestController::class, 'accept'])->name('admin.web.incoming-requests.accept');
  Route::get('/incoming-requests/{id}', [WebRequestController::class, 'show'])->name('admin.web.incoming-requests.show');
  Route::get('/incoming-requests/create/{id}', [WebRequestController::class, 'create'])->name('admin.web.incoming-requests.create');
  Route::post('/incoming-requests/store/{id}', [WebRequestController::class, 'store'])->name('admin.web.incoming-requests.store');
  Route::delete('/incoming-requests/destroy/{id}', [WebRequestController::class, 'destroy'])->name('admin.web.incoming-requests.destroy');

  // profile pages
  Route::view('/profile', 'admin.web-development.profile.index')->name('admin.web.profile');
  Route::view('/profile/edit', 'admin.web-development.profile.edit')->name('admin.web.profile.edit');
});
