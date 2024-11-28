<?php

use App\Http\Controllers\Api\Filter\ListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\PerguruanTinggiController;
use App\Http\Controllers\Api\ProdiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/statistics', [StatisticController::class, 'statistics']);
// Route::get('/statistics/jumlah-perguruan-tinggi-berdasarkan-wilayah', [StatisticController::class, 'jumlahPerguruanTinggiBerdasarkanWilayah']);
// Route::get('/statistics/jumlah-perguruan-tinggi-berdasarkan-bentuk-pt', [StatisticController::class, 'jumlahPerguruanTinggiBerdasarkanBentukPT']);
// Route::get('/statistics/jumlah-prodi-berdasarkan-program', [StatisticController::class, 'jumlahProdiBerdasarkanProgram']);
Route::get('/perguruan-tinggi', [PerguruanTinggiController::class, 'index']);
// Route::get('/perguruan-tinggi/{id}', [PerguruanTinggiController::class, 'show']);
// Route::get('/prodi', [ProdiController::class, 'index']);
// Route::get('/prodi/{id}', [ProdiController::class, 'show']);

// Route::get('/list', [ListController::class, 'getList']);
