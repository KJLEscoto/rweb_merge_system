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

Route::get('/', function () {
    return view('welcome');
})->name('landing.page');

// For smm operations
Route::view('/admin/smm/dashboard', 'admin.smm.dashboard')->name('admin.smm.dashboard');

// For front-end operations
Route::view('/admin/front-end/dashboard', 'admin.front-end.dashboard')->name('admin.front-end.dashboard');
Route::view('/admin/front-end/project-development', 'admin.front-end.project-development.index')->name('admin.front-end.project-development');
Route::view('/admin/front-end/project-development/1', 'admin.front-end.project-development.show')->name('admin.front-end.project-development.show');
Route::view('/admin/front-end/profile', 'admin.front-end.profile.index')->name('admin.front-end.profile');
Route::view('/admin/front-end/profile/edit', 'admin.front-end.profile.edit')->name('admin.front-end.profile.edit');
Route::view('/admin/front-end/revision-checklist', 'admin.front-end.revision-checklist.index')->name('admin.front-end.revision-checklist');
Route::view('/admin/front-end/revision-checklist/create', 'admin.front-end.revision-checklist.create')->name('admin.front-end.revision-checklist.create');
Route::view('/admin/front-end/promotions', 'admin.front-end.promotions')->name('admin.front-end.promotions');
Route::view('/admin/front-end/instructions-manual', 'admin.front-end.instructions-manual')->name('admin.front-end.instructions-manual');

Route::middleware('guest')->group(function () {

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
});

//register post method
Route::middleware(['auth', 'user_role:admin'])->group(function () {

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

Route::middleware(['auth', 'user_role:user'])->group(function () {

    //user dashboard
    Route::get('/intern/dashboard', [UserController::class, 'showUserDashboard'])->name('users.dashboard');

    //user settings
    Route::get('/settings', [UserController::class, 'showSettings'])->name('users.settings');

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