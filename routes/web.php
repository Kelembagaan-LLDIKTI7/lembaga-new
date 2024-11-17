<?php

use App\Http\Controllers\Akreditasi\AkreditasiPerguruanTinggiController;
use App\Http\Controllers\Akreditasi\AkreditasiProdiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dokumen\AktaBpController;
use App\Http\Controllers\Dokumen\PimpinanOrganisasiController;
use App\Http\Controllers\Dokumen\SkbpController;
use App\Http\Controllers\Dokumen\SkKumhamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\LembagaAkreditasiController;
use App\Http\Controllers\Master\OrganisasiTypeController;
use App\Http\Controllers\Master\PeringkatAkademiController;
use App\Http\Controllers\Organisasi\BadanPenyelenggaraController;
use App\Http\Controllers\Organisasi\PerguruanTinggiController;
use App\Http\Controllers\Organisasi\ProgramStudiController;
use App\Http\Controllers\Perkara\PerkaraOrganisasiController;
use App\Http\Controllers\Perkara\PerkaraOrganisasiPTController;
use App\Http\Controllers\Perkara\PerkaraProdiController;
use App\Http\Controllers\User\PermissionController;
use App\Http\Controllers\Pimpinan\PimpinanBadanPenyelenggaraController;
use App\Http\Controllers\Pimpinan\PimpinanPerguruanTinggiController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Sk\SkPerguruanTinggiController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Artisan;
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
        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('role.access:View Roles');
        Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('role.access:Create Roles');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('role.access:Edit Roles');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Roles');
    });

    //Actions Role Permissions Pengguna
    Route::get('/roles/{role}/give-permission', [RoleController::class, 'addPermissionToRole'])->name('addRolePermission')->middleware('role.access:View Role Permissions');
    Route::post('/roles/{role}/store-permission', [RoleController::class, 'storePermissionToRole'])->name('addRolePermission.store')->middleware('role.access:Add Role Permissions');

    // Routes for Permission
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware('role.access:View Permission');
        Route::post('/', [PermissionController::class, 'store'])->name('store')->middleware('role.access:Create Permission');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update')->middleware('role.access:Edit Permission');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Permission');
    });

    // Routes for User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('role.access:View User');
        Route::middleware('role.access:Create User')->group(function () {
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
        });
        Route::middleware('role.access:Edit User')->group(function () {
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
        });
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete User');
        Route::post('/user/update-password', [UserController::class, 'updatePassword'])->middleware('auth')->name('user.updatePassword');
    });

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/{id}', [DashboardController::class, 'show'])->name('show')->middleware('role.access:View Detail Perkara');
    });

    Route::prefix('peringkat-akademik')->name('peringkat-akademik.')->group(function () {
        Route::get('/', [PeringkatAkademiController::class, 'index'])->name('index')->middleware('role.access:View Peringkat Akreditasi');
        Route::post('/', [PeringkatAkademiController::class, 'store'])->name('store')->middleware('role.access:Create Peringkat Akreditasi');
        Route::middleware('role.access:Edit Peringkat Akreditasi')->group(function () {
            Route::get('/{id}/edit', [PeringkatAkademiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PeringkatAkademiController::class, 'update'])->name('update');
        });
        Route::delete('/{id}', [PeringkatAkademiController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Peringkat Akreditasi');
    });

    Route::prefix('lembaga-akademik')->name('lembaga-akademik.')->group(function () {
        Route::get('/', [LembagaAkreditasiController::class, 'index'])->name('index')->middleware('role.access:View Lembaga Akreditasi');
        Route::post('/', [LembagaAkreditasiController::class, 'store'])->name('store')->middleware('role.access:Create Lembaga Akreditasi');
        Route::middleware('role.access:Edit Lembaga Akreditasi')->group(function () {
            Route::get('/{id}/edit', [LembagaAkreditasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LembagaAkreditasiController::class, 'update'])->name('update');
        });
        Route::delete('/{id}', [LembagaAkreditasiController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Lembaga Akreditasi');
    });

    Route::prefix('organisasi-type')->name('organisasi-type.')->group(function () {
        Route::get('/', [OrganisasiTypeController::class, 'index'])->name('index')->middleware('role.access:View Jenis Organisasi');
        Route::post('/', [OrganisasiTypeController::class, 'store'])->name('store')->middleware('role.access:Create Jenis Organisasi');
        Route::middleware('role.access:Edit Jenis Organisasi')->group(function () {
            Route::get('/{id}/edit', [OrganisasiTypeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [OrganisasiTypeController::class, 'update'])->name('update');
        });
        Route::delete('/{id}', [OrganisasiTypeController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Jenis Organisasi');
    });

    Route::prefix('jabatan')->name('jabatan.')->group(function () {
        Route::get('/', [JabatanController::class, 'index'])->name('index')->middleware('role.access:View Jabatan');
        Route::middleware('role.access:Create Jabatan')->group(function () {
            Route::get('/create', [JabatanController::class, 'create'])->name('create');
            Route::post('/', [JabatanController::class, 'store'])->name('store');
        });
        Route::middleware('role.access:Edit Jabatan')->group(function () {
            Route::get('/{id}/edit', [JabatanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [JabatanController::class, 'update'])->name('update');
        });
        Route::delete('/{id}', [JabatanController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Jabatan');
    });

    Route::prefix('perguruan-tinggi')->name('perguruan-tinggi.')->group(function () {
        Route::get('/', [PerguruanTinggiController::class, 'index'])->name('index')->middleware('role.access:View Perguruan Tinggi');
        Route::middleware('role.access:Create Perguruan Tinggi')->group(function () {
            Route::get('/create', [PerguruanTinggiController::class, 'create'])->name('create');
            Route::post('/', [PerguruanTinggiController::class, 'store'])->name('store');
            Route::post('/validation-store', [PerguruanTinggiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Perguruan Tinggi')->group(function () {
            Route::get('/{id}/edit', [PerguruanTinggiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PerguruanTinggiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [PerguruanTinggiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [PerguruanTinggiController::class, 'show'])->name('show')->middleware('role.access:Detail Perguruan Tinggi');
        Route::delete('/{id}', [PerguruanTinggiController::class, 'destroy'])->name('destroy')->middleware('role.access:Delete Perguruan Tinggi');
        Route::post('/import', [PerguruanTinggiController::class, 'import'])->name('import')->middleware('role.access:Import Perguruan Tinggi');
    });

    Route::prefix('badan-penyelenggara')->name('badan-penyelenggara.')->group(function () {
        Route::get('/', [BadanPenyelenggaraController::class, 'index'])->name('index')->middleware('role.access:View Badan Penyelenggara');
        Route::middleware('role.access:Create Badan Penyelenggara')->group(function () {
            Route::get('/create', [BadanPenyelenggaraController::class, 'create'])->name('create');
            Route::post('/', [BadanPenyelenggaraController::class, 'store'])->name('store');
        });
        Route::middleware('role.access:Edit Badan Penyelenggara')->group(function () {
            Route::get('/{id}/edit', [BadanPenyelenggaraController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BadanPenyelenggaraController::class, 'update'])->name('update');
        });
        Route::get('/{id}', [BadanPenyelenggaraController::class, 'show'])->name('show')->middleware('role.access:Detail Badan Penyelenggara');
        Route::post('/import', [BadanPenyelenggaraController::class, 'import'])->name('import')->middleware('role.access:Import Badan Penyelenggara');
        Route::post('/validation-store-bp', [BadanPenyelenggaraController::class, 'validationStore'])->name('validationStore');
    });

    Route::prefix('program-studi')->name('program-studi.')->group(function () {
        Route::get('/', [ProgramStudiController::class, 'index'])->name('index')->middleware('role.access:View Program Studi');
        Route::middleware('role.access:Create Program Studi')->group(function () {
            Route::get('/{id}/create', [ProgramStudiController::class, 'create'])->name('create');
            Route::post('/', [ProgramStudiController::class, 'store'])->name('store');
            Route::post('/validation-store', [ProgramStudiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Program Studi')->group(function () {
            Route::get('/{id}/edit', [ProgramStudiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProgramStudiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [ProgramStudiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [ProgramStudiController::class, 'show'])->name('show')->middleware('role.access:Detail Program Studi');
    });

    Route::prefix('akreditasi-program-studi')->name('akreditasi-program-studi.')->group(function () {
        Route::get('/', [AkreditasiProdiController::class, 'index'])->name('index')->middleware('role.access:View Akreditasi Program Studi');
        Route::middleware('role.access:Create Akreditasi Program Studi')->group(function () {
            Route::get('/{id}/create', [AkreditasiProdiController::class, 'create'])->name('create');
            Route::post('/', [AkreditasiProdiController::class, 'store'])->name('store');
            Route::post('/validation-store', [AkreditasiProdiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Akreditasi Program Studi')->group(function () {
            Route::get('/{id}/edit', [AkreditasiProdiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AkreditasiProdiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [AkreditasiProdiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [AkreditasiProdiController::class, 'show'])->name('show')->middleware('role.access:Detail Akreditasi Program Studi');
        Route::post('/view-pdf', [AkreditasiProdiController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Akreditasi Program Studi');
    });

    Route::prefix('akreditasi-perguruan-tinggi')->name('akreditasi-perguruan-tinggi.')->group(function () {
        Route::get('/', [AkreditasiPerguruanTinggiController::class, 'index'])->name('index')->middleware('role.access:View Akreditasi Perguruan Tinggi');
        Route::middleware('role.access:Create Akreditasi Perguruan Tinggi')->group(function () {
            Route::get('/{id}/create', [AkreditasiPerguruanTinggiController::class, 'create'])->name('create');
            Route::post('/', [AkreditasiPerguruanTinggiController::class, 'store'])->name('store');
            Route::post('/validation-store', [AkreditasiPerguruanTinggiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Akreditasi Perguruan Tinggi')->group(function () {
            Route::get('/{id}/edit', [AkreditasiPerguruanTinggiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AkreditasiPerguruanTinggiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [AkreditasiPerguruanTinggiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}/get-akreditasi-detail', [AkreditasiPerguruanTinggiController::class, 'getAkreditasiDetail'])->name('getAkreditasiDetail')->middleware('role.access:Detail Akreditasi Perguruan Tinggi');
        Route::post('/view-pdf', [AkreditasiPerguruanTinggiController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Akreditasi Perguruan Tinggi');
    });

    Route::prefix('pimpinan-perguruan-tinggi')->name('pimpinan-perguruan-tinggi.')->group(function () {
        Route::get('/', [PimpinanPerguruanTinggiController::class, 'index'])->name('index')->middleware('role.access:View Pimpinan Perguruan Tinggi');
        Route::middleware('role.access:Create Pimpinan Perguruan Tinggi')->group(function () {
            Route::get('/{id}/create', [PimpinanPerguruanTinggiController::class, 'create'])->name('create');
            Route::post('/', [PimpinanPerguruanTinggiController::class, 'store'])->name('store');
            Route::post('/validation-store', [PimpinanPerguruanTinggiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Pimpinan Perguruan Tinggi')->group(function () {
            Route::get('/{id}/edit', [PimpinanPerguruanTinggiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PimpinanPerguruanTinggiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [PimpinanPerguruanTinggiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [PimpinanPerguruanTinggiController::class, 'show'])->name('show')->middleware('role.access:Detail Pimpinan Perguruan Tinggi');
        Route::post('/view-pdf', [PimpinanPerguruanTinggiController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Pimpinan Perguruan Tinggi');
    });

    Route::prefix('sk-perguruan-tinggi')->name('sk-perguruan-tinggi.')->group(function () {
        Route::get('/', [SkPerguruanTinggiController::class, 'index'])->name('index')->middleware('role.access:View SK Perguruan Tinggi');
        Route::middleware('role.access:Create SK Perguruan Tinggi')->group(function () {
            Route::get('/{id}/create', [SkPerguruanTinggiController::class, 'create'])->name('create');
            Route::post('/', [SkPerguruanTinggiController::class, 'store'])->name('store');
            Route::post('/validation-store', [SkPerguruanTinggiController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit SK Perguruan Tinggi')->group(function () {
            Route::get('/{id}/edit', [SkPerguruanTinggiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SkPerguruanTinggiController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [SkPerguruanTinggiController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [SkPerguruanTinggiController::class, 'show'])->name('show')->middleware('role.access:Detail SK Perguruan Tinggi');
        Route::post('/view-pdf', [SkPerguruanTinggiController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF SK Perguruan Tinggi');
    });

    Route::prefix('pimpinan-badan-penyelenggara')->name('pimpinan-badan-penyelenggara.')->group(function () {
        Route::get('/', [PimpinanBadanPenyelenggaraController::class, 'index'])->name('index')->middleware('role.access:View Pimpinan Badan Penyelenggara');
        Route::middleware('role.access:Create Pimpinan Badan Penyelenggara')->group(function () {
            Route::get('/{id}/create', [PimpinanBadanPenyelenggaraController::class, 'create'])->name('create');
            Route::post('/', [PimpinanBadanPenyelenggaraController::class, 'store'])->name('store');
            Route::post('/validation-store', [PimpinanBadanPenyelenggaraController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit Pimpinan Badan Penyelenggara')->group(function () {
            Route::get('/{id}/edit', [PimpinanBadanPenyelenggaraController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PimpinanBadanPenyelenggaraController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [PimpinanBadanPenyelenggaraController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [PimpinanBadanPenyelenggaraController::class, 'show'])->name('show')->middleware('role.access:Detail Pimpinan Badan Penyelenggara');;
        Route::post('/view-pdf', [PimpinanBadanPenyelenggaraController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Pimpinan Badan Penyelenggara');;
    });

    Route::prefix('akta-badan-penyelenggara')->name('akta-badan-penyelenggara.')->group(function () {
        Route::get('/', [AktaBpController::class, 'index'])->name('index')->middleware('role.access:View Akta Badan Penyelenggara');
        Route::middleware('role.access:Create Akta Badan Penyelenggara')->group(function () {
            Route::get('/{id}/create', [AktaBpController::class, 'create'])->name('create');
            Route::post('/', [AktaBpController::class, 'store'])->name('store');
            Route::post('/validation-store', [AktaBpController::class, 'validationStore'])->name('validationStore');
        });

        Route::middleware('role.access:Edit Akta Badan Penyelenggara')->group(function () {
            Route::get('/{id}/edit', [AktaBpController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AktaBpController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update-akta', [AktaBpController::class, 'validationUpdateAkta'])->name('validationUpdate');
        });

        Route::get('/{id}', [AktaBpController::class, 'show'])->name('show')->middleware('role.access:Detail Akta Badan Penyelenggara');
        Route::post('/view-pdf', [AktaBpController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Akta Badan Penyelenggara');
    });

    Route::prefix('sk-kumham')->name('sk-kumham.')->group(function () {
        Route::get('/', [SkKumhamController::class, 'index'])->name('index')->middleware('role.access:View SK Kumham Badan Penyelenggara');
        Route::middleware('role.access:Create SK Kumham Badan Penyelenggara')->group(function () {
            Route::get('/{id}/create', [SkKumhamController::class, 'create'])->name('create');
            Route::post('/', [SkKumhamController::class, 'store'])->name('store');
            Route::post('/validation-store', [SkKumhamController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit SK Kumham Badan Penyelenggara')->group(function () {
            Route::get('/{id}/edit', [SkKumhamController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SkKumhamController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [SkKumhamController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}', [SkKumhamController::class, 'show'])->name('show')->middleware('role.access:Detail SK Kumham Badan Penyelenggara');
        Route::post('/view-pdf', [SkKumhamController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF Badan Penyelenggara');
    });

    Route::prefix('skbp-badan-penyelenggara')->name('skbp-badan-penyelenggara.')->group(function () {
        Route::middleware('role.access:Create SK Badan Penyelenggara')->group(function () {
            Route::get('/{id}/create', [SkbpController::class, 'create'])->name('create');
            Route::post('/', [SkbpController::class, 'store'])->name('store');
            Route::post('/validation-store', [SkbpController::class, 'validationStore'])->name('validationStore');
        });
        Route::middleware('role.access:Edit SK Badan Penyelenggara')->group(function () {
            Route::get('/{id}/edit', [SkbpController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SkbpController::class, 'update'])->name('update');
            Route::put('/{id}/validation-update', [SkbpController::class, 'validationUpdate'])->name('validationUpdate');
        });
        Route::get('/{id}/view-pdf', [SkbpController::class, 'viewPdf'])->name('viewPdf')->middleware('role.access:View PDF SK Badan Penyelenggara');
    });

    Route::prefix('perkara-organisasi')->name('perkara-organisasi.')->group(function () {
        Route::middleware('role.access:Create Perkara Badan Penyelenggara')->group(function () {
            Route::get('/{id}/create', [PerkaraOrganisasiController::class, 'create'])->name('create');
            Route::post('/', [PerkaraOrganisasiController::class, 'store'])->name('store');
        });
        Route::get('/{id}', [PerkaraOrganisasiController::class, 'show'])->name('show')->middleware('role.access:View Detail Perkara Badan Penyelenggara');
        Route::patch('/{id}/status-update', [PerkaraOrganisasiController::class, 'updateStatus'])->name('status-update')->middleware('role.access:Update Status Perkara Badan Penyelenggara');
    });

    Route::prefix('perkara-organisasipt')->name('perkara-organisasipt.')->group(function () {
        Route::middleware('role.access:Create Perkara Perguruan Tinggi')->group(function () {
            Route::get('/{id}/create', [PerkaraOrganisasiPTController::class, 'create'])->name('create');
            Route::post('/', [PerkaraOrganisasiPTController::class, 'store'])->name('store');
            Route::post('/validation-store', [PerkaraOrganisasiPTController::class, 'validationStore'])->name('validationStore');
        });
        Route::get('/{id}', [PerkaraOrganisasiPTController::class, 'show'])->name('show')->middleware('role.access:View Detail Perkara Perguruan Tinggi');
        Route::middleware('role.access:Update Status Perkara Perguruan Tinggi')->group(function () {
            Route::patch('/{id}/status-update', [PerkaraOrganisasiPTController::class, 'updateStatus'])->name('status-update');
            Route::put('/{id}/validation-update', [PerkaraOrganisasiPTController::class, 'validationUpdate'])->name('validationUpdate');
        });
    });

    Route::prefix('perkara-prodi')->name('perkara-prodi.')->group(function () {
        Route::middleware('role.access:Create Perkara Program Studi')->group(function () {
            Route::get('/{id}/create', [PerkaraProdiController::class, 'create'])->name('create');
            Route::post('/', [PerkaraProdiController::class, 'store'])->name('store');
            Route::post('/validation-store', [PerkaraProdiController::class, 'validationStore'])->name('validationStore');
        });
        Route::get('/{id}', [PerkaraProdiController::class, 'show'])->name('show')->middleware('role.access:View Detail Perkara Program Studi');
        Route::middleware('role.access:Update Status Perkara Program Studi')->group(function () {
            Route::patch('/{id}/status-update', [PerkaraProdiController::class, 'updateStatus'])->name('status-update');
            Route::put('/{id}/validation-update', [PerkaraProdiController::class, 'validationUpdate'])->name('validationUpdate');
        });
    });

    Route::get('/generate', function () {
        Artisan::call('storage:link');
        return 'Storage complete!';
    });
    
    Route::get('/optimize', function () {
        Artisan::call('optimize');
        return 'Optimization complete!';
    });
});
