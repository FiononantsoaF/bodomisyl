<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\AppointmentsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/service-category', ServiceCategoryController::class);
// Route::apiResource('/services', ServicesController::class);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/employees', [EmployeesController::class, 'index']);

Route::post('/appointments', [AppointmentsController::class, 'create']);