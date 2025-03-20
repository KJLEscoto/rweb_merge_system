<?php


//for smm routes package
use App\Http\Controllers\AdminSupervisorRequestController;
use App\Http\Controllers\ClientApprovalController;
use App\Http\Controllers\ClientHistoryController;
use App\Http\Controllers\ClientRenewalController;
use App\Http\Controllers\ContentApprovalController;
use App\Http\Controllers\ContentHistoryController;
use App\Http\Controllers\ContentRevisionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GraphicApprovalController;
use App\Http\Controllers\GraphicHistoryController;
use App\Http\Controllers\GraphicRevisionController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\JobOrderTrackerController;
use App\Http\Controllers\OperationApprovalController;
use App\Http\Controllers\OperationHistoryController;
use App\Http\Controllers\OperationRenewalController;
use App\Http\Controllers\OperationRevisionController;
use App\Http\Controllers\OperationTaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectEndorsementFormController;
use App\Http\Controllers\RequestFormController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SupervisorApprovalController;
use App\Http\Controllers\SupervisorDirectJobOrderController;
use App\Http\Controllers\SupervisorHistoryController;
use App\Http\Controllers\SupervisorJobOrderController;
use App\Http\Controllers\SupervisorRenewalController;
use App\Http\Controllers\SupervisorRevisionController;
use App\Http\Controllers\SupervisorTaskController;
use App\Http\Controllers\TopApprovalController;
use App\Models\ProjectEndorsementForm;
use Illuminate\Support\Facades\Route;

Route::prefix('/smm')->middleware('auth.redirect')->group(function () {
  Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.smm.dashboard');
  });


  Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/client', [ClientApprovalController::class, 'index'])->name('admin.smm.client.approve');
    Route::get('/client/show/{id}', [ClientApprovalController::class, 'show'])->name('admin.smm.client.show');
    Route::get('/client/edit/{id}', [ClientApprovalController::class, 'edit'])->name('client.edit');
    Route::put('/client/update/{id}', [ClientApprovalController::class, 'update'])->name('client.update');
    Route::get('/client/decline/{id}', [ClientApprovalController::class, 'declineForm']);
    Route::post('/client/decline/{id}', [ClientApprovalController::class, 'decline']);
    Route::put('/client/renew/{id}', [ClientApprovalController::class, 'renew'])->name('client.renew');

    Route::get('/client/history', [ClientHistoryController::class, 'index'])->name('admin.smm.client.history');
    Route::get('/client/history/show/{id}', [ClientHistoryController::class, 'show'])->name('client.history.show');
    Route::get('/client/history/download/{id}', [ClientHistoryController::class, 'downloadPDF'])->name('client.history.download');

    Route::get('/client/renewal', [ClientRenewalController::class, 'index'])->name('admin.smm.client.renewal');
    Route::post('/client/update/{id}', [ClientRenewalController::class, 'update'])->name('client.update');
  });


  Route::middleware(['auth', 'role:content_writer'])->group(function () {
    Route::get('/content', [ContentApprovalController::class, 'index'])->name('admin.smm.content.approve');
    Route::get('/content/show/{id}', [ContentApprovalController::class, 'show'])->name('admin.smm.content.show');
    Route::get('/content/create/{id}', [ContentApprovalController::class, 'create'])->name('admin.smm.content.create');
    Route::put('/content/store/{id}', [ContentApprovalController::class, 'store'])->name('content.store');
    Route::get('/content/edit/{id}', [ContentApprovalController::class, 'edit'])->name('content.edit');
    Route::put('/content/update/{id}', [ContentApprovalController::class, 'update'])->name('content.update');
    Route::put('/content/accept/{id}', [ContentApprovalController::class, 'accept'])->name('content.accept');

    Route::get('/content/revisions/', [ContentRevisionController::class, 'index'])->name('content.revisions');
    Route::get('/content/revisions/edit/{id}', [ContentRevisionController::class, 'edit']);
    Route::put('/content/revisions/update/{id}', [ContentRevisionController::class, 'update']);
    Route::put('/content/revisions/update/{id}', [ContentRevisionController::class, 'update']);

    Route::get('/content/history', [ContentHistoryController::class, 'index'])->name('admin.smm.content.history');
    Route::get('/content/history/show/{id}', [ContentHistoryController::class, 'show'])->name('content.history.show');
    Route::get('/content/history/download/{id}', [ContentHistoryController::class, 'downloadPDF'])->name('content.history.download');
  });

  Route::middleware(['auth', 'role:graphic_designer'])->group(function () {
    Route::get('/graphic', [GraphicApprovalController::class, 'index'])->name('admin.smm.graphic.approve');
    Route::get('/graphic/show/{id}', [GraphicApprovalController::class, 'show'])->name('admin.smm.graphic.show');
    Route::get('/graphic/create/{id}', [GraphicApprovalController::class, 'create'])->name('admin.smm.graphic.create');
    Route::put('/graphic/store/{id}', [GraphicApprovalController::class, 'store'])->name('graphic.store');
    Route::get('/graphic/edit/{id}', [GraphicApprovalController::class, 'edit'])->name('graphic.edit');
    Route::put('/graphic/update/{id}', [GraphicApprovalController::class, 'update'])->name('graphic.update');
    Route::put('/graphic/accept/{id}', [GraphicApprovalController::class, 'accept'])->name('graphic.accept');

    Route::get('/graphic/revisions', [GraphicRevisionController::class, 'index'])->name('graphic.revisions');
    Route::get('/graphic/revisions/edit/{id}', [GraphicRevisionController::class, 'edit']);
    Route::put('/graphic/revisions/update/{id}', [GraphicRevisionController::class, 'update']);

    Route::get('/graphic/history', [GraphicHistoryController::class, 'index'])->name('admin.smm.graphic.history');
    Route::get('/graphic/history/show/{id}', [GraphicHistoryController::class, 'show'])->name('graphic.history.show');
    Route::get('/graphic/history/download/{id}', [GraphicHistoryController::class, 'downloadPDF'])->name('graphic.history.download');
  });
});

Route::prefix('/admin/smm')->middleware('auth.redirect')->group(function () {

  //smm routes
  Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.smm.dashboard');
    Route::get('/revision', [RevisionController::class, 'index'])->name('admin.smm.revision');
    Route::get('/revision/show/{id}', [RevisionController::class, 'show'])->name('admin.smm.revision.show');
    Route::get('/revision/edit/{id}', [RevisionController::class, 'edit'])->name('admin.smm.revision.edit');
    Route::put('/revision/update/{id}', [RevisionController::class, 'update'])->name('revision.update');


    Route::name('admin.smm.')->group(function () {
      Route::resource('track', JobOrderTrackerController::class);
      Route::get('/track/show/draft/{id}', [JobOrderTrackerController::class, 'showDraft'])->name('track.showDraft');
      Route::get('/track/edit/draft/{id}', [JobOrderTrackerController::class, 'editDraft'])->name('track.editDraft');
      Route::delete('/track/delete/draft/{id}', [JobOrderTrackerController::class, 'destroyDraft'])->name('track.destroyDraft');
    });


    Route::put('/signature/store', [SignatureController::class, 'store'])->name('signature.store');

    Route::get('requestForm/history', [RequestFormController::class, 'history'])->name('admin.smm.requestForm.history');
    Route::get('requestForm/create', [RequestFormController::class, 'create'])->name('admin.smm.requestForm');
    Route::post('requestForm/store', [RequestFormController::class, 'store']);
    Route::post('requestForm/approve/{id}', [RequestFormController::class, 'approve'])->name('requestForm.approve');
    Route::get('requestForm/show/{id}', [RequestFormController::class, 'show'])->name('admin.smm.requestForm.show');
    Route::get('requestForm/edit/{id}', [RequestFormController::class, 'edit'])->name('admin.smm.requestForm.edit');
    Route::put('requestForm/update/{id}', [RequestFormController::class, 'update']);
    Route::delete('requestForm/delete/{id}', [RequestFormController::class, 'delete']);

    Route::get('endorsement', [ProjectEndorsementFormController::class, 'index'])->name('admin.smm.endorsement');
    Route::get('endorsement/create', [ProjectEndorsementFormController::class, 'create'])->name('admin.smm.endorsement.create');
    Route::post('endorsement/store', [ProjectEndorsementFormController::class, 'store'])->name('admin.smm.endorsement.store');
    Route::get('endorsement/{id}/show', [ProjectEndorsementFormController::class, 'show'])->name('admin.smm.endorsement.show');

    Route::put('endorsement/{id}/approve', [ProjectEndorsementFormController::class, 'approve'])->name('admin.smm.endorsement.approve');
  });

  Route::middleware('auth')->group(function () {
    Route::get('/profile/show', [ProfileController::class, 'index'])->name('admin.smm.profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('admin.smm.profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // JobOrderController
    Route::get('/joborder', [JobOrderController::class, 'index'])->name('admin.smm.joborder');
    Route::get('/joborder/create', [JobOrderController::class, 'create'])->name('joborder.create');
    Route::post('/joborder/store', [JobOrderController::class, 'store'])->name('joborder.store');
    Route::get('/joborder/show/{id}', [JobOrderController::class, 'show'])->name('admin.smm.joborder.show');
    Route::get('/joborder/edit/{id}', [JobOrderController::class, 'edit'])->name('admin.smm.joborder.edit');
    Route::put('/joborder/update/{id}', [JobOrderController::class, 'update'])->name('joborder.update');
  });



  Route::middleware(['auth', 'role:assistant_supervisor'])->group(function () {
    Route::get('/operation', [OperationApprovalController::class, 'index'])->name('admin.smm.operation.approve');
    Route::get('/operation/show/{id}', [OperationApprovalController::class, 'show'])->name('admin.smm.operation.show');
    Route::get('/operation/edit/{id}', [OperationApprovalController::class, 'edit'])->name('operation.edit');
    Route::put('/operation/update/{id}', [OperationApprovalController::class, 'update'])->name('operation.update');
    Route::get('/operation/decline/{id}', [OperationApprovalController::class, 'declineForm']);
    Route::post('/operation/decline/{id}', [OperationApprovalController::class, 'decline']);

    Route::get('/operation/history', [OperationHistoryController::class, 'index'])->name('admin.smm.operation.history');
    Route::get('/operation/history/show/{id}', [OperationHistoryController::class, 'show'])->name('operation.history.show');
    Route::get('/operation/history/download/{id}', [OperationHistoryController::class, 'downloadPDF'])->name('operation.history.download');

    Route::get('/operation/renewal', [OperationRenewalController::class, 'index'])->name('admin.smm.operation.renewal');
    Route::post('/operation/update/{id}', [OperationRenewalController::class, 'update'])->name('operation.update');

    Route::get('/operation/requests', [AdminSupervisorRequestController::class, 'index'])->name('admin.smm.operation.request');
    Route::get('/operation/request/show/{id}', [AdminSupervisorRequestController::class, 'show'])->name('admin.smm.operation.show');
    Route::get('/operation/request/create/{id}', [AdminSupervisorRequestController::class, 'create'])->name('admin.smm.operation.create');
    Route::post('/operation/request/store', [AdminSupervisorRequestController::class, 'store'])->name('operation.store');
    Route::put('/operation/request/accept/{id}', [AdminSupervisorRequestController::class, 'accept'])->name('operation.accept');

    //Create "My Tasks" tab for Admin DONE
    Route::get('/operation/task', [OperationTaskController::class, 'index'])->name('admin.smm.operation.task');
    Route::get('/operation/task/show/{id}', [OperationTaskController::class, 'show'])->name('admin.smm.operation.show');
    Route::get('/operation/task/create/{id}', [OperationTaskController::class, 'create'])->name('admin.smm.operation.create');
    Route::put('/operation/task/store/{id}', [OperationTaskController::class, 'store'])->name('operation.store');
    Route::get('/operation/task/edit/{id}', [OperationTaskController::class, 'edit'])->name('admin.smm.operation.edit');
    Route::put('/operation/task/update/{id}', [OperationTaskController::class, 'update'])->name('operation.update');
    Route::put('/operation/task/accept/{id}', [OperationTaskController::class, 'accept'])->name('operation.accept');

    //Create "My Revisions" tab for Admin DONE
    Route::get('/operation/revision', [OperationRevisionController::class, 'index'])->name('admin.smm.operation.revision');
    Route::get('/operation/revision/edit/{id}', [OperationRevisionController::class, 'edit'])->name('admin.smm.operation.edit');
    Route::get('/operation/revision/show/{id}', [OperationRevisionController::class, 'show'])->name('admin.smm.operation.show');
    Route::put('/operation/revision/update/{id}', [OperationRevisionController::class, 'update'])->name('operation.update');

    //Letter
    Route::view('/operation/letter', 'admin.smm.letter.index')->name('admin.smm.letter.index');
  });

  Route::middleware(['auth', 'role:top_manager'])->group(function () {
    Route::get('/topmanager', [TopApprovalController::class, 'index'])->name('admin.smm.topmanager.approve');
    Route::get('/topmanager/show/{id}', [TopApprovalController::class, 'show'])->name('admin.smm.topmanager.show');
    Route::get('/topmanager/edit/{id}', [TopApprovalController::class, 'edit'])->name('topmanager.edit');
    Route::put('/topmanager/update/{id}', [TopApprovalController::class, 'update'])->name('topmanager.update');
    Route::get('/topmanager/decline/{id}', [TopApprovalController::class, 'declineForm']);
    Route::post('/topmanager/decline/{id}', [TopApprovalController::class, 'decline']);
  });

  Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/supervisor/approve', [SupervisorApprovalController::class, 'index'])->name('admin.smm.supervisor.approve');
    Route::get('/supervisor/approve/show/{id}', [SupervisorApprovalController::class, 'show'])->name('admin.smm.supervisor.show');
    Route::get('/supervisor/approve/edit/{id}', [SupervisorApprovalController::class, 'edit'])->name('supervisor.edit');
    Route::put('/supervisor/approve/update/{id}', [SupervisorApprovalController::class, 'update'])->name('supervisor.update');
    Route::get('/supervisor/approve/declineForm/{id}', [SupervisorApprovalController::class, 'declineForm'])->name('supervisor.declineForm');
    Route::put('/supervisor/approve/decline/{id}', [SupervisorApprovalController::class, 'decline'])->name('supervisor.decline');

    Route::get('/supervisor/history', [SupervisorHistoryController::class, 'index'])->name('admin.smm.supervisor.history');
    Route::get('/supervisor/history/show/{id}', [SupervisorHistoryController::class, 'show'])->name('supervisor.history.show');
    Route::get('/supervisor/history/download/{id}', [SupervisorHistoryController::class, 'downloadPDF'])->name('supervisor.history.download');

    Route::get('/supervisor/renewal', [SupervisorRenewalController::class, 'index'])->name('admin.smm.supervisor.renewal');
    Route::post('/supervisor/update/{id}', [SupervisorRenewalController::class, 'update'])->name('supervisor.update');

    Route::get('/supervisor/joborder', [SupervisorJobOrderController::class, 'index'])->name('admin.smm.supervisor.joborder');
    Route::get('/supervisor/joborder/create', [SupervisorJobOrderController::class, 'create'])->name('admin.smm.supervisor.create');
    Route::post('/supervisor/joborder/store', [SupervisorJobOrderController::class, 'store'])->name('supervisor.store');
    Route::get('/supervisor/joborder/show/{id}', [SupervisorJobOrderController::class, 'show'])->name('admin.smm.supervisor.show');
    Route::get('/supervisor/joborder/delete/{id}', [SupervisorJobOrderController::class, 'delete'])->name('admin.smm.supervisor.delete');
    Route::get('/supervisor/joborder/edit/{id}', [SupervisorJobOrderController::class, 'edit'])->name('admin.smm.supervisor.edit');
    Route::put('/supervisor/joborder/update/{id}', [SupervisorJobOrderController::class, 'update'])->name('supervisor.update');

    Route::get('/supervisor/directjob', [SupervisorDirectJobOrderController::class, 'index'])->name('admin.smm.supervisor.directjob');
    Route::get('/supervisor/directjob/create', [SupervisorDirectJobOrderController::class, 'create'])->name('admin.smm.supervisor.directjob.create');
    Route::post('/supervisor/directjob/store', [SupervisorDirectJobOrderController::class, 'store'])->name('supervisor.directjob.store');
    Route::get('/supervisor/directjob/show/{id}', [SupervisorDirectJobOrderController::class, 'show'])->name('admin.smm.supervisor.directjob.show');
    Route::get('/supervisor/directjob/edit/{id}', [SupervisorDirectJobOrderController::class, 'edit'])->name('admin.smm.supervisor.directjob.edit');
    Route::put('/supervisor/directjob/update/{id}', [SupervisorDirectJobOrderController::class, 'update'])->name('supervisor.directjob.update');

    //Create "My Task" tab for Supervisor DONE
    Route::get('/supervisor/task', [SupervisorTaskController::class, 'index'])->name('admin.smm.supervisor.task');
    Route::get('/supervisor/task/show/{id}', [SupervisorTaskController::class, 'show'])->name('admin.smm.supervisor.show');
    Route::get('/supervisor/task/create/{id}', [SupervisorTaskController::class, 'create'])->name('admin.smm.supervisor.create');
    Route::put('/supervisor/task/store/{id}', [SupervisorTaskController::class, 'store'])->name('supervisor.store');
    Route::get('/supervisor/task/edit/{id}', [SupervisorTaskController::class, 'edit'])->name('admin.smm.supervisor.edit');
    Route::put('/supervisor/task/update/{id}', [SupervisorTaskController::class, 'update'])->name('supervisor.update');
    Route::put('/supervisor/task/accept/{id}', [SupervisorTaskController::class, 'accept'])->name('supervisor.accept');

    //Create "My Revisions" tab for Supervisor  DONE
    Route::get('/supervisor/revision', [SupervisorRevisionController::class, 'index'])->name('supervisor.revision');
    Route::get('/supervisor/revision/edit/{id}', [SupervisorRevisionController::class, 'edit'])->name('supervisor.edit');
    Route::put('/supervisor/revision/update/{id}', [SupervisorRevisionController::class, 'update'])->name('supervisor.update');
  });
});
