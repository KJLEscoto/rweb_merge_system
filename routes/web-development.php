<?php

use App\Http\Controllers\WebDirectJobOrderController;
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
  // Route::view('/direct-job-order/{id}/edit', 'admin.web-development.direct-job-order.edit')->name('admin.web.direct-job-order.edit');

  // operation job order pages
  Route::view('/operation-job-order', 'admin.web-development.operation-job-order.index')->name('admin.web.operation-job-order');
  Route::view('/operation-job-order/create', 'admin.web-development.operation-job-order.create')->name('admin.web.operation-job-order.create');
  Route::view('/operation-job-order/{id}/edit', 'admin.web-development.operation-job-order.edit')->name('admin.web.operation-job-order.edit');

  // task pages
  Route::view('/task', 'admin.web-development.task.index')->name('admin.web.task');
  Route::view('/task/create/{id}', 'admin.web-development.task.create')->name('admin.web.task.create');

  // revision pages
  Route::view('/revision', 'admin.web-development.revision.index')->name('admin.web.revision');

  // approvals pages
  Route::view('/approvals', 'admin.web-development.approvals.index')->name('admin.web.approvals');

  // users pages
  // Route::view('/users', 'admin.web-development.users.index')->name('admin.web.users');
  Route::get('/users', [WebUserController::class, 'index'])->name('admin.web.users');
  Route::get('/users/create', [WebUserController::class, 'create'])->name('admin.web.users.create');
  Route::post('/users/store', [WebUserController::class, 'store'])->name('admin.web.users.store');
  Route::get('/users/{id}', [WebUserController::class, 'show'])->name('admin.web.users.show');
  Route::get('/users/edit/{id}', [WebUserController::class, 'edit'])->name('admin.web.users.edit');

  // track pages
  Route::view('/track', 'admin.web-development.track.index')->name('admin.web.track');
  Route::view('/track/{id}', 'admin.web-development.track.show')->name('admin.web.track.show');
  Route::view('/track/{track_id}/draft/{draft_id}', 'admin.web-development.track.draft.show')->name('admin.web.track.draft.show');
  Route::view('/track/{track_id}/draft/{draft_id}/edit', 'admin.web-development.track.draft.edit')->name('admin.web.track.draft.edit');

  // downloadables pages
  Route::view('/downloadables', 'admin.web-development.downloadables.index')->name('admin.web.downloadables');

  // instructions manual pages
  Route::view('/instructions-manual', 'admin.web-development.instructions-manual.index')->name('admin.web.instructions-manual');

  // profile pages
  Route::view('/profile', 'admin.web-development.profile.index')->name('admin.web.profile');
  Route::view('/profile/edit', 'admin.web-development.profile.edit')->name('admin.web.profile.edit');
});
