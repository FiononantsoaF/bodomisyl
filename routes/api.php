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
use App\Http\Controllers\Api\PromotionsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrangeMoneyController;
use App\Http\Controllers\Api\ClientsFilesApiController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\CarteCadeauServiceApiController;



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
// Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services', [ServiceCategoryController::class, 'services']);

Route::get('/creneaus', [CreneauController::class, 'index']);

Route::get('/employees', [EmployeesController::class, 'index']);

Route::post('/appointments', [AppointmentsController::class, 'create']);

Route::get('/appointments/client/{id}', [AppointmentsController::class, 'getappointbyclient']);



Route::get('/subscription/client/{id}', [SubscriptionController::class, 'getsubscriptionbyclient']);
Route::post('/client/login', [ClientsController::class, 'loginclient']);

Route::post('/checkcreneau', [ServiceCategoryController::class, 'checkcreneaux']);

Route::post('/client/changepass', [ClientsController::class, 'changepassword']);

Route::get('/appointmentsall', [AppointmentsController::class, 'getallappointments']);

Route::get('/services-promotions', [PromotionsController::class, 'getServicesWithPromotions']);

Route::post('/password-reset-request', [AuthController::class, 'sendResetEmail']);

Route::post('/change-password', [ClientsController::class, 'changepassword']);

Route::post('/user/update',[ClientsController::class, 'update']);
Route::get('/check-email', [ClientsController::class, 'checkEmail']);
Route::get('/getClientById', [ClientsController::class, 'getClientById']);
Route::post('/check-password', [ClientsController::class, 'checkPassword']);

// paiement mvola 
Route::post('/mvola', [MvolaController::class, 'payIn']);
Route::put('/mvola/callback', [MvolaController::class, 'callback']);
Route::get('/mvola/status/{reference}', [MvolaController::class, 'checkStatus']);

Route::post('/stripe/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
Route::post('/payments/confirm-stripe', [StripeController::class, 'storeStripe']);

Route::post('/orangemoney/uuid', [OrangeMoneyController::class, 'uuid']);
Route::post('/orangemoney/pocess-payment', [OrangeMoneyController::class, 'processPayement']);
Route::post('/orangemoney/status', [OrangeMoneyController::class, 'statuspaiement']);

Route::get('/clientsfiles/{client_id}', [ClientsFilesApiController::class, 'show']);

Route::get('/temoignage', [TestimonialController::class, 'index']);
Route::get('/cartecadeauservice',[CarteCadeauServiceApiController::class, 'index']);
Route::post('/cartecadeauservice/create',[CarteCadeauServiceApiController::class, 'save']);


Route::get('/cartecadeaubycode', [CarteCadeauServiceApiController::class, 'getCartecadeauByCode']);
Route::get('/cartecadeaubyclient', [CarteCadeauServiceApiController::class, 'getCartecadeauByClient']);


// Chatbot 
Route::post('/chat', [ChatController::class, 'chat']);

