<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactsController;
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


Route::controller(BlogController::class)->group(function () {
    Route::get('/', 'index')->name('blog.index');
    Route::get('/posts/{post}', 'show')->name('blog.show');
});

Route::controller(ContactsController::class)->group(function () {
    Route::get('/contacts', 'index')->name('contacts.index');
    Route::post('/contacts', 'submit')->name('contacts.submit');
});

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], static function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::group(['middleware' => 'auth'], static function () {
        Route::resource('posts', PostController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
        Route::resource('questions', QuestionController::class)->except('show', 'create', 'store');
    });
});
