<?php

use App\Http\Controllers\Akreditasi\AkreditasiProdiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\LembagaAkreditasiController;
use App\Http\Controllers\Master\OrganisasiTypeController;
use App\Http\Controllers\Master\PeringkatAkademiController;
use App\Http\Controllers\Organisasi\BadanPenyelenggaraController;
use App\Http\Controllers\Organisasi\PerguruanTinggiController;
use App\Http\Controllers\Organisasi\ProgramStudiController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Routes for Role
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
    });

    //Actions Role Permissions Pengguna
    Route::get('/roles/{role}/give-permission', [RoleController::class, 'addPermissionToRole'])->name('addRolePermission');
    Route::post('/roles/{role}/store-permission', [RoleController::class, 'storePermissionToRole'])->name('addRolePermission.store');

    // Routes for Permission
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    });

    // Routes for User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/create', [UserController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::prefix('peringkat-akademik')->name('peringkat-akademik.')->group(function () {
        Route::get('/', [PeringkatAkademiController::class, 'index'])->name('index');
    });

    Route::prefix('lembaga-akademik')->name('lembaga-akademik.')->group(function () {
        Route::get('/', [LembagaAkreditasiController::class, 'index'])->name('index');
    });

    Route::prefix('organisasi-type')->name('organisasi-type.')->group(function () {
        Route::get('/', [OrganisasiTypeController::class, 'index'])->name('index');
    });

    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::get('/', [JabatanController::class, 'index'])->name('index');
    });

    Route::prefix('perguruan-tinggi')->name('perguruan-tinggi.')->group(function () {
        Route::get('/', [PerguruanTinggiController::class, 'index'])->name('index');
        Route::get('/create', [PerguruanTinggiController::class, 'create'])->name('create');
        Route::post('/', [PerguruanTinggiController::class, 'store'])->name('store');
        Route::get('/{id}', [PerguruanTinggiController::class, 'show'])->name('show');
    });

    Route::prefix('badan-penyelenggara')->name('badan-penyelenggara.')->group(function () {
        Route::get('/', [BadanPenyelenggaraController::class, 'index'])->name('index')->middleware('role.access:View Badan Penyelenggara');
        Route::get('/create', [BadanPenyelenggaraController::class, 'create'])->name('create');
        Route::post('/', [BadanPenyelenggaraController::class, 'store'])->name('store');
        Route::get('/{id}', [BadanPenyelenggaraController::class, 'show'])->name('show');
    });

    Route::prefix('program-studi')->name('program-studi.')->group(function () {
        Route::get('/', [ProgramStudiController::class, 'index'])->name('index');
        Route::get('/{id}/create', [ProgramStudiController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [ProgramStudiController::class, 'edit'])->name('edit');
        Route::post('/', [ProgramStudiController::class, 'store'])->name('store');
        Route::put('/{id}', [ProgramStudiController::class, 'update'])->name('update');
        Route::get('/{id}', [ProgramStudiController::class, 'show'])->name('show');
    });

    Route::prefix('akreditasi-program-studi')->name('akreditasi-program-studi.')->group(function () {
        Route::get('/', [AkreditasiProdiController::class, 'index'])->name('index');
        Route::get('/{id}/create', [AkreditasiProdiController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [AkreditasiProdiController::class, 'edit'])->name('edit');
        Route::post('/', [AkreditasiProdiController::class, 'store'])->name('store');
        Route::put('/{id}', [AkreditasiProdiController::class, 'update'])->name('update');
        Route::get('/{id}', [AkreditasiProdiController::class, 'show'])->name('show');
    });
});
