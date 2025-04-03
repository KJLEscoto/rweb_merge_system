<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('smm/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.smm.login');

    Route::post('smm/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('admin/smm/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('admin.smm.password.request');

    Route::post('admin/smm/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('admin.smm.password.email');

    Route::get('admin/smm/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('admin.smm.password.reset');

    Route::post('admin/smm/reset-password', [NewPasswordController::class, 'store'])
        ->name('admin.smm.password.store');
});

Route::middleware(['auth', 'signature'])->group(function () {
    Route::get('admin/smm/users', [RegisteredUserController::class, 'index'])
        ->name('admin.smm.users');

    Route::get('admin/smm/users/show/{id}', [RegisteredUserController::class, 'show'])
        ->name('admin.smm.users.show');
    Route::get('admin/smm/users/edit/{id}', [RegisteredUserController::class, 'edit'])
        ->name('admin.smm.users.edit');
    Route::put('admin/smm/users/update/{id}', [RegisteredUserController::class, 'update'])
        ->name('admin.smm.users.update');
    Route::delete('admin/smm/users/destroy/{id}', [RegisteredUserController::class, 'destroy'])
        ->name('admin.smm.users.destroy');

    Route::get('admin/smm/register', [RegisteredUserController::class, 'create'])
        ->name('admin.smm.register');

    Route::post('/admin/smm/register', [RegisteredUserController::class, 'store']);

    Route::get('admin/smm/verify-email', EmailVerificationPromptController::class)
        ->name('admin.smm.verification.notice');

    Route::get('admin/smm/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('admin.smm.verification.verify');

    Route::post('admin/smm/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('admin.smm.verification.send');

    Route::get('admin/smm/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('admin.smm.password.confirm');

    Route::post('admin/smm/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('admin/smm/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('admin/smm/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('admin.smm.logout');
});
