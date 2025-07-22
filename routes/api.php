<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceCategoryController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\AppointmentsController;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\MvolaController;
use App\Http\Controllers\Api\CreneauController;
use App\Http\Controllers\Api\StripeController;


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
Route::get('/creneaus', [CreneauController::class, 'index']);

Route::get('/employees', [EmployeesController::class, 'index']);

Route::post('/appointments', [AppointmentsController::class, 'create']);

Route::get('/appointments/client/{id}', [AppointmentsController::class, 'getappointbyclient']);

Route::get('/subscription/client/{id}', [SubscriptionController::class, 'getsubscriptionbyclient']);
Route::post('/client/login', [ClientsController::class, 'loginclient']);
Route::post('/client/changepass', [ClientsController::class, 'changepassword']);

Route::get('/appointmentsall', [AppointmentsController::class, 'getallappointments']);


// paiement mvola 
Route::post('/mvola', [MvolaController::class, 'payIn']);

Route::post('/stripe/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
Route::post('/payments/confirm-stripe', [StripeController::class, 'storeStripe']);


