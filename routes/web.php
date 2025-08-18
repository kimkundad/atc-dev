<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['UserRole:superadmin|admin']], function () {

    // Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard.index');

    // QR Code
    Route::get('/admin/qrcode', [App\Http\Controllers\QRCodeController::class, 'index'])
        ->name('qrcode.index');
    Route::get('/admin/qrcode/create', [App\Http\Controllers\QRCodeController::class, 'create'])
        ->name('qrcode.create');
    Route::get('/admin/qrcode/edit/{id}', [App\Http\Controllers\QRCodeController::class, 'edit'])
        ->name('qrcode.edit');
    Route::post('/admin/qrcode', [App\Http\Controllers\QRCodeController::class, 'store'])
        ->name('qrcode.store');

    // ล็อตนัมเบอร์
    Route::get('/admin/lots', [App\Http\Controllers\LotNumberController::class, 'index'])
        ->name('lots.index');
    Route::get('/admin/lots/create', [App\Http\Controllers\LotNumberController::class, 'create'])
        ->name('lots.create');
         Route::get('/admin/lots/edit/{id}', [App\Http\Controllers\LotNumberController::class, 'edit'])
        ->name('lots.edit');
    Route::post('/admin/lots', [App\Http\Controllers\LotNumberController::class, 'store'])
        ->name('lots.store');

    // ผู้ใช้งานระบบ
    Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\UserController::class, 'create'])
        ->name('users.create');
    Route::post('/admin/users', [App\Http\Controllers\UserController::class, 'store'])
        ->name('users.store');
    Route::get('/admin/users/{id}/edit/', [App\Http\Controllers\UserController::class, 'edit'])
        ->name('users.edit');
    Route::get('/admin/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])
        ->name('activity-logs.activity');


        Route::get('admin/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('admin/profile/general',  [ProfileController::class, 'updateGeneral'])->name('profile.update.general');
    Route::put('admin/profile/account',  [ProfileController::class, 'updateAccount'])->name('profile.update.account');

});
