<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HRD\MKaryawanController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/karyawan', [MKaryawanController::class, 'index']);

});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/karyawan', [MKaryawanController::class, 'index']);
});
