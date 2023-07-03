<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/* AUTH */
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\MDepartemenController;
use App\Http\Controllers\Master\MPenempatanController;
use App\Http\Controllers\Master\MBagianController;
use App\Http\Controllers\Master\MJabatanController;
use App\Http\Controllers\Master\MKaryawanController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\RoleController;

/* SETTING */
use App\Http\Controllers\Setting\SettingController;

/* TRANSACTIONS */
use App\Http\Controllers\Transaction\AbsensiKaryawanController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/profile', [AuthController::class, 'profile']);

    Route::prefix('/departemen')->group(function () {
        Route::get('/', [MDepartemenController::class, 'index']);
        Route::post('/store', [MDepartemenController::class, 'store']);
        Route::get('/{id}', [MDepartemenController::class, 'show']);
        Route::post('/{id}/update', [MDepartemenController::class, 'update']);
        Route::delete('/{id}/destroy', [MDepartemenController::class, 'destroy']);
    });

    Route::prefix('/penempatan')->group(function () {
        Route::get('/', [MPenempatanController::class, 'index']);
        Route::post('/store', [MPenempatanController::class, 'store']);
        Route::get('/{id}', [MPenempatanController::class, 'show']);
        Route::post('/{id}/update', [MPenempatanController::class, 'update']);
        Route::delete('/{id}/destroy', [MPenempatanController::class, 'destroy']);
    });

    Route::prefix('/bagian')->group(function () {
        Route::get('/', [MBagianController::class, 'index']);
        Route::post('/store', [MBagianController::class, 'store']);
        Route::get('/{id}', [MBagianController::class, 'show']);
        Route::post('/{id}/update', [MBagianController::class, 'update']);
        Route::delete('/{id}/destroy', [MBagianController::class, 'destroy']);
    });

    Route::prefix('/jabatan')->group(function () {
        Route::get('/', [MJabatanController::class, 'index']);
        Route::post('/store', [MJabatanController::class, 'store']);
        Route::get('/{id}', [MJabatanController::class, 'show']);
        Route::post('/{id}/update', [MJabatanController::class, 'update']);
        Route::delete('/{id}/destroy', [MJabatanController::class, 'destroy']);
    });

    Route::prefix('/karyawan')->group(function () {
        Route::get('/', [MKaryawanController::class, 'index']);
        Route::post('/store', [MKaryawanController::class, 'store']);
        Route::get('/{id}', [MKaryawanController::class, 'show']);
        Route::post('/{id}/update', [MKaryawanController::class, 'update']);
        Route::delete('/{id}/destroy', [MKaryawanController::class, 'destroy']);
    });

    Route::prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/{id}/update', [UserController::class, 'update']);
        Route::delete('/{id}/destroy', [UserController::class, 'destroy']);
    });

    Route::prefix('/role')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/menus', [RoleController::class, 'menus']);
        Route::post('/store', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/{id}/update', [RoleController::class, 'update']);
        Route::delete('/{id}/destroy', [RoleController::class, 'destroy']);
    });

    Route::prefix('/setting')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/update', [SettingController::class, 'update']);
        Route::get('/{name}', [SettingController::class, 'show']);
    });

    Route::prefix('/absensi')->group(function () {
        Route::get('/', [AbsensiKaryawanController::class, 'index']);
        Route::post('/import', [AbsensiKaryawanController::class, 'import']);
        Route::get('/{id}', [AbsensiKaryawanController::class, 'show']);
        Route::post('/{id}/update', [AbsensiKaryawanController::class, 'update']);
        Route::delete('/{id}/destroy', [AbsensiKaryawanController::class, 'destroy']);
        Route::post('/update_absent', [AbsensiKaryawanController::class, 'updateAbsentDays']);
        Route::post('/update_overtime', [AbsensiKaryawanController::class, 'updateOvertimeHour']);
        Route::post('/update_absent_detail', [AbsensiKaryawanController::class, 'updateAbsentDetail']);
    });

});
