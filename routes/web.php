<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\PublicQrController;

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
    return redirect('/admin/dashboard');
});

Route::get('/home', function () {
    return redirect('/admin/dashboard');
});

Auth::routes();

Route::get('/qr/{code}', [PublicQrController::class, 'show'])
    ->name('public.qr');



Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['UserRole:SuperAdmin|Supervisor|Admin']], function () {

    // Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard.index');

    // QR Code
    // Route::get('/admin/qrcode',           [QRCodeController::class, 'index'])->name('qrcode.index');
    // Route::get('/admin/qrcode/create',    [QRCodeController::class, 'create'])->name('qrcode.create');
    // Route::get('/admin/qrcode/edit/{id}', [QRCodeController::class, 'edit'])->name('qrcode.edit');
    // Route::post('/admin/qrcode',          [QRCodeController::class, 'store'])->name('qrcode.store');
    // Route::put ('admin/qrcode/{qr}',      [QrCodeController::class,'update'])->name('qrcode.update');
    Route::resource('admin/qrcode', App\Http\Controllers\QRCodeController::class);

    Route::get('/admin/qrcode/{qr}/download', [QRCodeController::class, 'download'])->name('qrcode.download');

    // Ajax (อยู่คอนโทรลเลอร์เดียวกัน)
    Route::get('admin/qrcode/ajax/lots-by-category/{category}',
        [QrCodeController::class,'ajaxLotsByCategory'])->name('qrcode.ajax.lots-by-category');

    Route::get('admin/qrcode/ajax/lot-detail/{lot}',
        [QrCodeController::class,'ajaxLotDetail'])->name('qrcode.ajax.lot-detail');


    // AJAX: ดึงล็อตตามประเภท
    Route::get('/admin/api/lots/by-category/{category}',
        [App\Http\Controllers\QRCodeController::class, 'lotsByCategory']
    )->name('ajax.lots-by-category');


    // AJAX: ดึงล็อตตามประเภท
    Route::get('/admin/api/lots/by-category2/{category}',
        [App\Http\Controllers\QRCodeController::class, 'lotsByCategory2']
    )->name('ajax.lots-by-category2');

    // AJAX: รายละเอียดล็อต (ไว้เติมช่องข้อมูลสินค้า)
    Route::get('/admin/api/lots/{lot}',
        [App\Http\Controllers\QRCodeController::class, 'lotDetail']
    )->name('ajax.lot-detail');

    // เพิ่มอันนี้
    Route::put('/admin/qrcode/{id}',      [QRCodeController::class, 'update'])->name('qrcode.update');
    // Route::delete('/admin/qrcode/{id}', [QRCodeController::class, 'destroy'])->name('qrcode.destroy');

    // ล็อตนัมเบอร์
    Route::get('/admin/lots', [App\Http\Controllers\LotNumberController::class, 'index'])
        ->name('lots.index');
    Route::get('/admin/lots/create', [App\Http\Controllers\LotNumberController::class, 'create'])
        ->name('lots.create');
        Route::get('/admin/lots/edit/{lot}', [App\Http\Controllers\LotNumberController::class, 'edit'])
    ->name('lots.edit');
Route::put('/admin/lots/{lot}', [App\Http\Controllers\LotNumberController::class, 'update'])
    ->name('lots.update');
    Route::post('/admin/lots', [App\Http\Controllers\LotNumberController::class, 'store'])
        ->name('lots.store');

        Route::get('/admin/lots/{lot}', [App\Http\Controllers\LotNumberController::class, 'show'])
    ->name('lots.show');

        Route::delete('/admin/lots/{id}', [App\Http\Controllers\LotNumberController::class, 'destroy'])
    ->name('lots.destroy');

       Route::get('products/by-category/{id}', [App\Http\Controllers\LotNumberController::class, 'productsByCategory'])
         ->name('ajax.products-by-category');


         Route::get('/admin/ajax/products-by-category',
    [App\Http\Controllers\LotNumberController::class, 'productsByCategory2']
)->name('ajax.products-by-category2');

Route::get('/admin/api/lot-no/check/{lot_no}', [App\Http\Controllers\LotNumberController::class, 'checkLotNoDuplicate'])
    ->name('ajax.lotno-check');


    // ผู้ใช้งานระบบ
    Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\UserController::class, 'create'])
        ->name('users.create');
    Route::post('/admin/users', [App\Http\Controllers\UserController::class, 'store'])
        ->name('users.store');
    Route::get('admin/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])
        ->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\UserController::class, 'update'])
        ->name('users.update');

    Route::get('/admin/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])
        ->name('activity-logs.activity');


        Route::delete('/admin/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])
        ->name('users.destroy');

    // (ถ้าอยากมีกู้คืนด้วย)
    Route::post('/admin/users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])
        ->name('users.restore');


        Route::get('admin/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('admin/profile/general',  [ProfileController::class, 'updateGeneral'])->name('profile.update.general');
    Route::put('admin/profile/account',  [ProfileController::class, 'updateAccount'])->name('profile.update.account');


    Route::get('/ajax/lot-next/{product}', [App\Http\Controllers\AjaxLotController::class, 'next'])
    ->name('ajax.lot-next');

});
