<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DtrDownloadRequestController;
use App\Http\Controllers\DtrSummaryController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SearchController;
use App\Models\Notification;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;

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
use App\Http\Controllers\WebUserController;
use App\Models\Page;
use App\Models\Privilege;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    return view('welcome');
})->name('landing.page');

// For smm operations
//Route::view('/admin/smm/dashboard', 'admin.smm.dashboard')->name('admin.smm.dashboard');

// For front-end operations
Route::view('/admin/front-end/dashboard', 'admin.web-development.dashboard')->name('admin.web-development.dashboard');
Route::view('/admin/front-end/project-development', 'admin.front-end.project-development.index')->name('admin.front-end.project-development');
Route::view('/admin/front-end/project-development/1', 'admin.front-end.project-development.show')->name('admin.front-end.project-development.show');
Route::view('/admin/front-end/profile', 'admin.front-end.profile.index')->name('admin.front-end.profile');
Route::view('/admin/front-end/profile/edit', 'admin.front-end.profile.edit')->name('admin.front-end.profile.edit');
Route::view('/admin/front-end/revision-checklist', 'admin.front-end.revision-checklist.index')->name('admin.front-end.revision-checklist');
Route::view('/admin/front-end/revision-checklist/create', 'admin.front-end.revision-checklist.create')->name('admin.front-end.revision-checklist.create');
Route::view('/admin/front-end/promotions', 'admin.front-end.promotions')->name('admin.front-end.promotions');
Route::view('/admin/front-end/instructions-manual', 'admin.front-end.instructions-manual')->name('admin.front-end.instructions-manual');

Route::middleware('guest')->group(function () {});
//users page transition
Route::get('/users', [UserController::class, 'showUsers'])->name('show.users');

//register page transition
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');

//login post method
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('show.admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');

//register post method
$admin_roles = ['admin', 'operations', 'supervisor', 'top_manager'];
Route::middleware(['auth', 'user_role:' . implode(',', $admin_roles)])->group(function () {

    //Route::resource('admin.dtr.schools', SchoolController::class);
    Route::get('/admin/dtr/schools', [SchoolController::class, 'index'])->name('admin.dtr.schools');

    Route::get('/admin/dtr/schools/{id}', [SchoolController::class, 'show'])->name('admin.dtr.schools.show');
    Route::put('/admin/dtr/schools/{id}', [SchoolController::class, 'update'])->name('admin.dtr.schools.show.update');

    Route::get('/admin/dtr/dashboard', [UserController::class, 'showAdminDashboard'])->name('admin.dtr.dashboard');
    Route::get('/admin/dtr/interns', [UserController::class, 'showAdminUsers'])->name('admin.dtr.interns');

    //admin user dtr page

    Route::get('/admin/dtr/interns/create', [AuthController::class, 'showAdminUsersCreate'])->name('admin.dtr.interns.create');

    Route::post('/admin/dtr/interns/create', [AuthController::class, 'showAdminUsersCreatePost'])->name('admin.dtr.interns.create.post');

    //users specific profile
    Route::get('/admin/dtr/interns/{id}', [UserController::class, 'showUserDetails'])->name('admin.dtr.interns.details');

    Route::get('/admin/dtr/interns/{id}/edit', [UserController::class, 'showEditUsers'])->name('admin.dtr.interns.details.edit');

    Route::post('/admin/dtr/interns/{id}/edit', [UserController::class, 'showEditUsersPost'])->name('admin.dtr.interns.details.edit.post');

    Route::get('/admin/dtr/interns/{id}/dtr', [DtrSummaryController::class, 'showAdminUserDtr'])->name('admin.dtr.interns.dtr');

    Route::post('/admin/dtr/interns/{id}/dtr/post', [DtrSummaryController::class, 'ShowAdminUserDtrPagination'])->name('admin.dtr.interns.dtr.post');

    //admin history
    Route::get('/admin/dtr/history', [UserController::class, 'showAdminHistory'])->name('admin.dtr.history');
    Route::get('/admin/dtr/history/create', [UserController::class, 'showAdminCreateHistory'])->name('admin.dtr.history.create');
    Route::post('/admin/dtr/history/create', [UserController::class, 'createAdminHistory'])->name('admin.dtr.history.create.post');
    Route::get('/admin/dtr/history/{id}/edit', [UserController::class, 'showAdminHistoryEdit'])->name('admin.dtr.history.edit');
    Route::put('/admin/dtr/history/{id}/edit', [UserController::class, 'editAdminHistory'])->name('admin.dtr.history.edit.post');

    //admin profile
    Route::get('/admin/dtr/profile', [UserController::class, 'showAdminProfile'])->name('admin.dtr.profile');
    Route::get('/admin/dtr/profile/edit', [UserController::class, 'showAdminProfileEdit'])->name('admin.dtr.profile.edit');

    Route::get('/admin/dtr/approvals', [UserController::class, 'showAdminApprovals'])->name('admin.dtr.approvals');

    Route::get('/admin/dtr/approvals/{id}', [DtrSummaryController::class, 'showAdminUserApprovalDtr'])->name('admin.dtr.approvals.show');

    Route::post('/admin-dtr-approve', [DtrDownloadRequestController::class, 'approve'])->name('admin.dtr.approve');

    Route::post('/admin-dtr-batch-approve', [DtrDownloadRequestController::class, 'batchApprove'])->name('admin.dtr.batch.approve');
    Route::post('/admin-dtr-decline', [DtrDownloadRequestController::class, 'decline'])->name('admin.dtr.decline');
    Route::post('/admin-dtr-batch-decline', [DtrDownloadRequestController::class, 'batchDecline'])->name('admin.dtr.batch.decline');

    Route::get('/read-notifications-index', [NotificationController::class, 'readAdminNotification'])->name('admin.dtr.recieve.notification');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'readAdminNotification'])->name('admin.dtr.recieve.notification');

    //scanner user validation data
    Route::get('scanner/{qr_code}', [UserController::class, 'AdminScannerValidation'])->name('admin.dtr.scanner.validation');
    Route::post('/history', [UserController::class, 'AdminScannerTimeCheck'])->name('admin.dtr.history.time.check');
    Route::post('/admin/dtr/history/search', [SearchController::class, 'searchHistory'])->name('admin.dtr.history.search');
});

Route::view('/admin/dtr/school/create', 'admin.dtr.schools.create')->name('admin.dtr.schools.create');
Route::post('/admin/dtr/schools/create/post', [SchoolController::class, 'store'])->name('admin.dtr.schools.create.post');

$user_roles = ['user'];
Route::middleware(['auth', 'user_role:' . implode(',', $user_roles)])->group(function () {

    //user dashboard
    Route::get('/intern/dashboard', [UserController::class, 'showUserDashboard'])->name('users.dashboard');

    //user settings
    Route::get('/intern/settings', [UserController::class, 'showSettings'])->name('users.settings');

    //dtr page
    Route::get('/intern/dtr', [DtrSummaryController::class, 'showUserDtr'])->name('users.dtr');
    Route::get('/intern/dtr/summary', [DtrSummaryController::class, 'showUserDtrSummary'])->name('users.dtr.summary');
    Route::post('/intern/dtr/post', [DtrSummaryController::class, 'ShowUserDtrPagination'])->name('users.dtr.post');

    Route::get('/intern/request', [UserController::class, 'showRequest'])->name('users.request');
    Route::get('/intern/request/{id}', [DtrSummaryController::class, 'showUserRequestedDtr'])->name('users.request.show');

    //gdrive test api page
    Route::get('/apiTest', function () {
        return view('gdrive.files');
    });
});

//update user post method
Route::put('/intern/update', [UserController::class, 'update'])->name('users.settings.update');

//update admin post method
Route::put('/admin/dtr/update', [UserController::class, 'adminUpdate'])->name('admin.dtr.settings.update');

Route::post('/send-notification', [NotificationController::class, 'sendAdminNotification'])->name('user.send.request.download.notification');

//logout post method
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//admin history post method
Route::post('/download-pdf', [PDFController::class, 'download'])->name('download.pdf');
Route::post('/admin/dtr/download-pdf', [PDFController::class, 'admin_download'])->name('admin.dtr.download.pdf');

//forgot-password page transition
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('show.reset-password');
//reset-password post method
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
//reset-password-validation post method
Route::post('/reset-password-validation', [EmailController::class, 'EmailResetPasswordValidation'])->name('reset-password-validation');

//test routes

Route::get('/notification-page', [NotificationController::class, 'readUserNotification'])->name('user.recieve.notification');


Route::post('/notifications/{id}/archive', [NotificationController::class, 'archiveAdminNotification'])->name('user.recieve.notification.archive');

//test routes

//test routes
Route::get('/notification-index-test', [NotificationController::class, 'receiveNotificationIndex'])->name('receive.notification');
Broadcast::routes(['middleware' => ['auth']]);

//test route for auth
Route::post("/pusher/auth", function (Request $request) {
    // Ensure the user is logged in
    if (!$request->user()) {
        return response()->json(["message" => "Unauthorized"], 403);
    }

    $pusher = new Pusher\Pusher(
        env("PUSHER_APP_KEY"),
        env("PUSHER_APP_SECRET"),
        env("PUSHER_APP_ID"),
        ["cluster" => env("PUSHER_APP_CLUSTER"), "useTLS" => true]
    );

    $socketId = $request->input("socket_id");
    $channelName = $request->input("channel_name");

    // Extract user ID from channel name
    preg_match('/private-notifications\.(\d+)/', $channelName, $matches);
    $userId = $matches[1] ?? null;

    if ($userId && $userId == auth()->id()) {
        $auth = $pusher->authorizeChannel($channelName, $socketId);
        return response()->json($auth);
    }

    return response()->json(["message" => "Forbidden"], 403);
});

Route::view('/read-form', 'admin.dtr.files.show')->name('admin.dtr.files.show');
Route::view('/upload-form', 'admin.dtr.files.index')->name('admin.dtr.files');
Route::view('/forbidden', 'forbidden')->name('forbidden');

//files
//Route::resource('/files', FileController::class);

Route::prefix('files')->group(function () {
    Route::get('/', [FileController::class, 'index'])->name('files.index');
    Route::post('/', [FileController::class, 'store'])->name('files.store');
    Route::get('/{file}', [FileController::class, 'show'])->name('files.show');
    Route::delete('/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});


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



    Route::middleware(['auth', 'role:operations'])->group(function () {
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
        Route::get('/operation/revision/edit/{id}', [OperationRevisionController::class, 'edit'])->name('operation.edit');
        Route::put('/operation/revision/update/{id}', [OperationRevisionController::class, 'update'])->name('operation.update');
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

// web development routes
//pages

if (Schema::hasTable('pages') && Schema::hasTable('privileges')) {
    $dashboard          = optional(Page::where('description', 'like', '%dashboard%')->first())->id;
    $direct_job_order   = optional(Page::where('description', 'like', '%direct_job_order%')->first())->id;
    $operation_job_order = optional(Page::where('description', 'like', '%operation_job_order%')->first())->id;
    $task               = optional(Page::where('description', 'like', '%task%')->first())->id;
    $revision           = optional(Page::where('description', 'like', '%revision%')->first())->id;
    $approvals          = optional(Page::where('description', 'like', '%approvals%')->first())->id;
    $track              = optional(Page::where('description', 'like', '%track%')->first())->id;
    $users              = optional(Page::where('description', 'like', '%users%')->first())->id;
    $downloadables      = optional(Page::where('description', 'like', '%downloadables%')->first())->id;
    $profile            = optional(Page::where('description', 'like', '%profile%')->first())->id;

    // Privileges
    $can_read   = optional(Privilege::where('description', 'like', '%can_read%')->first())->id;
    $can_update = optional(Privilege::where('description', 'like', '%can_update%')->first())->id;
    $can_create = optional(Privilege::where('description', 'like', '%can_create%')->first())->id;
    $can_delete = optional(Privilege::where('description', 'like', '%can_delete%')->first())->id;
} else {
    // Handle the case where the tables do not exist
    return response()->json(['error' => 'Required tables do not exist in the system_merge database.'], 500);
}


//web development routes
Route::prefix('/admin/web_development')->group(function () use (
    $dashboard,
    $direct_job_order,
    $operation_job_order,
    $task,
    $revision,
    $approvals,
    $track,
    $users,
    $downloadables,
    $profile,
    $can_read,
    $can_update,
    $can_create,
    $can_delete,
) {
    // Route::get('/supervisor/directjob', [Dashboard::class, 'index'])
    // Route::prefix('dashboard')->group(function () use ($dashboard, $can_read, $can_update, $can_create, $can_delete) {
    //     Route::get('/', function () {
    //         @dd('success!');
    //     })->middleware("role_channel:$dashboard, $can_read");
    // });
    Route::get('/dashboard', function () {
        return view('admin.web-development.dashboard');
    })->name('admin.web.dashboard');

    Route::get('/123')->name('admin.web.direct-job-order');
    Route::get('/321')->name('admin.web.revision-checklist');
    Route::get('/231')->name('admin.web.promotions');
    Route::get('/421')->name('admin.web.instructions-manual');
    Route::get('/425')->name('admin.web.profile');
    Route::get('/423')->name('admin.web.instructions-manual');
    Route::get('/442')->name('admin.web.instructions-manual');
    Route::get('/users', [WebUserController::class, 'index'])->name('admin.web.users');
});

require __DIR__ . '/auth.php';