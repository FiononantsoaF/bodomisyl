<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\AppointmentdbController;
use App\Http\Controllers\SessiondbController;
use App\Http\Controllers\ServicedbController;
use App\Http\Controllers\ServiceCategorydbController;
use App\Http\Controllers\ServiceSessiondbController;
use App\Http\Controllers\SubscriptiondbController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorydbController;
use App\Http\Controllers\CreneaudbController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\ClientdbController;
use App\Http\Controllers\EmployeedbController;
use App\Http\Controllers\Api\MvolaController;
use App\Http\Controllers\PaymentdbController;
use App\Http\Controllers\EmployeesCreneaudbController;
use App\Http\Controllers\CurrencydbController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PromotiondbController;
use App\Http\Controllers\JobCategoryController;



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
    return view('auth.login');
})->name('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/changestate/{id}', [DashboardController::class, 'changestate'])->name('dashboard.changestate');

    //appointment
    Route::get('/appointmentsdb', [AppointmentdbController::class, 'index'])->name('appointmentsdb');
    Route::post('/appointmentsdb/changestate/{id}', [AppointmentdbController::class, 'changestate'])->name('appointmentsdb.changestate');
    Route::post('/appointmentsdb/creation', [AppointmentdbController::class, 'creation'])->name('appointmentsdb.creation');
    
    //session
    Route::get('/session', [SessiondbController::class, 'index'])->name('sessiondb');
    Route::get('/session/create', [SessiondbController::class, 'create'])->name('sessiondb.create');
    Route::post('/session/store', [SessiondbController::class, 'store'])->name('sessiondb.store');
    Route::get('/session/edit/{id}', [SessiondbController::class, 'edit'])->name('sessiondb.edit');
    Route::patch('/session/update/{id}', [SessiondbController::class, 'update'])->name('sessiondb.update');
    //service
    Route::get('/services', [ServicedbController::class, 'index'])->name('servicedb');
    Route::get('/services/create', [ServicedbController::class, 'create'])->name('servicedb.create');
    Route::post('/services/store', [ServicedbController::class, 'store'])->name('servicedb.store');
    Route::get('/services/edit/{id}', [ServicedbController::class, 'edit'])->name('servicedb.edit');
    Route::patch('/services/update', [ServicedbController::class, 'update'])->name('servicedb.update');
    Route::delete('/services/{id}', [ServicedbController::class, 'destroy'])->name('servicedb.destroy');

    // category session
    Route::get('/category', [CategorydbController::class, 'index'])->name('categorydb');
    Route::get('/category/create', [CategorydbController::class, 'create'])->name('categorydb.create');
    Route::post('/category/store', [CategorydbController::class, 'store'])->name('categorydb.store');
    Route::get('/service-category', [ServiceCategorydbController::class, 'index'])->name('service-categorydb');

    // creneau
    Route::get('/creneau', [CreneaudbController::class, 'index'])->name('creneaudb');
    Route::get('/creneau/create', [CreneaudbController::class, 'create'])->name('creneaudb.create');
    Route::post('/creneau/store', [CreneaudbController::class, 'store'])->name('creneaudb.store');
    Route::post('/creneau/upadtecrenau', [CreneaudbController::class, 'updatecreneau'])->name('creneaudb.updatecreneau');
    
    // promotions
    Route::get('promotion',[PromotiondbController::class, 'index'])->name('promotiondb');
    Route::get('/promotion/create', [PromotiondbController::class, 'create'])->name('promotiondb.create');
    Route::post('/promotion/store', [PromotiondbController::class, 'store'])->name('promotiondb.store');
    Route::get('/promotion/edit/{id}', [PromotiondbController::class, 'edit'])->name('promotiondb.edit');
    Route::delete('/promotion/destroy/{id}', [PromotiondbController::class, 'destroy'])->name('promotiondb.destroy');
    Route::post('/promotion/update/{id}', [PromotiondbController::class, 'update'])->name('promotiondb.update');
    
    //subscription
    Route::get('/subscriptiondb', [SubscriptiondbController::class, 'index'])->name('subscriptiondb');
    Route::get('/subscription/appoint/{id}', [SubscriptiondbController::class, 'continue'])->name('subscriptiondb.appoint');
    // destroy
    Route::delete('/service-category/{id}', [ServiceCategorydbController::class, 'destroy'])->name('service-categorydb.destroy');
    Route::get('/service-category/create', [ServiceCategorydbController::class, 'create'])->name('service-categorydb.create');
    Route::post('/service-category/store', [ServiceCategorydbController::class, 'store'])->name('service-categorydb.store');
    Route::get('/service-category/edit/{id}', [ServiceCategorydbController::class, 'edit'])->name('service-categorydb.edit');
    Route::patch('/service-category/update', [ServiceCategorydbController::class, 'update'])->name('service-categorydb.update');
    Route::get('/service-session', [ServiceSessiondbController::class, 'index'])->name('service-session');
    Route::get('/service-session/create', [ServiceSessiondbController::class, 'create'])->name('service-session.create');
    Route::post('/service-session/store', [ServiceSessiondbController::class, 'store'])->name('service-session.store');
    Route::get('/user/index', [UserController::class, 'index'])->name('userdb');

    // userdb.create
    Route::get('/user/create', [UserController::class, 'create'])->name('userdb.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('userdb.store');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('userdb.edit');
    Route::patch('/user/update/{id}', [UserController::class, 'update'])->name('userdb.update');
    // userdb.edit

    // calendar
    Route::get('/calendar', [GoogleCalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [GoogleCalendarController::class, 'store'])->name('calendar.store');
    Route::put('/calendar/{id}', [GoogleCalendarController::class, 'update'])->name('calendar.update');
    Route::delete('/calendar/{id}', [GoogleCalendarController::class, 'destroy'])->name('calendar.destroy');
    Route::post('/calendar/sync', [GoogleCalendarController::class, 'syncEvents'])->name('calendar.sync');

    //client
    Route::get('/client', [ClientdbController::class, 'index'])->name('clientdb');
    Route::get('/client/create', [ClientdbController::class, 'create'])->name('clientdb.create');
    Route::post('/client/store', [ClientdbController::class, 'store'])->name('clientdb.store');
    Route::post('/client/changepassword', [ClientdbController::class, 'changepassword'])->name('clientdb.changepassowrd');

    // employee
    Route::get('/employee', [EmployeedbController::class, 'index'])->name('employeedb');
    Route::get('/employee/create', [EmployeedbController::class, 'create'])->name('employeedb.create');
    Route::post('/employee/store', [EmployeedbController::class, 'store'])->name('employeedb.store');
    Route::get('/employee/edit/{id}', [EmployeedbController::class, 'edit'])->name('employeedb.edit');
    Route::patch('/employee/update/{id}', [EmployeedbController::class, 'update'])->name('employeedb.update');
    Route::patch('/employee/{id}/desactiver', [EmployeedbController::class, 'desactiver'])->name('employeedb.desactiver');

    // employee creneau
    Route::get('/employees-creneau', [EmployeesCreneaudbController::class, 'index'])->name('employees-creneaudb');
    Route::get('/employees-creneau/create', [EmployeesCreneaudbController::class, 'create'])->name('employees-creneaudb.create');
    Route::post('/employees-creneau/store', [EmployeesCreneaudbController::class, 'store'])->name('employees-creneaudb.store');
    Route::get('/employees-creneau/search', [EmployeesCreneaudbController::class, 'searchByName']);
    Route::post('/employees-creneau/upadtecrenau', [EmployeesCreneaudbController::class, 'updatecreneau'])->name('employees-creneaudb.updatecreneau');
    Route::get('/employees-creneaux/{employee_id}', [EmployeesCreneaudbController::class, 'getCreneaux']);
    Route::post('/employee-creneau/destroy',[EmployeesCreneaudbController::class, 'destroy'])->name('employees-creneaudb.delete');

    Route::get('/job', [JobCategoryController::class, 'index'])->name('jobdb');
    Route::get('/job/edit/{id}', [JobCategoryController::class, 'edit'])->name('jobdb.edit');
    Route::get('/job/create', [JobCategoryController::class, 'create'])->name('jobdb.create');
    Route::post('/job/store', [JobCategoryController::class, 'store'])->name('jobdb.store');
    Route::delete('/job/{id}', [JobCategoryController::class, 'destroy'])->name('jobdb.destroy');
    Route::patch('/job/update/{id}', [JobCategoryController::class, 'update'])->name('jobdb.update');


    //fiche suivi client et paiement
    Route::get('/fiche/{id}', [PaymentdbController::class, 'index'])->name('fichedb');
    Route::post('/fiche-create', [PaymentdbController::class, 'createfiche'])->name('create-fichedb');
    Route::get('/payment/payment', [PaymentdbController::class, 'payment'])->name('paymentdb');
    
    //currency
    Route::get('/currency', [CurrencydbController::class, 'index'])->name('currencydb');
    Route::get('/currency/create', [CurrencydbController::class, 'create'])->name('currencydb.create');
    Route::post('/currency/store', [CurrencydbController::class, 'store'])->name('currencydb.store');
    Route::get('/currency/edit/{id}', [CurrencydbController::class, 'edit'])->name('currencydb.edit');
    Route::post('/currency/upadte', [CurrencydbController::class, 'update'])->name('currencydb.update');

    // export
    Route::get('/export-subscriptions', [ExportController::class, 'exportSubscriptions'])->name('export.subscriptions');
    Route::get('/export-appointments', [ExportController::class, 'exportAppointments'])->name('export.appointments');
    Route::get('/export-appointmentsday', [ExportController::class, 'exportAppointmentsDay'])->name('export.appointmentsday');
    Route::get('/export-employees', [ExportController::class, 'exportEmployees'])->name('export.employees');
    // Route::get('/export-appointments-range', [ExportController::class, 'exportAppointmentsRange'])->name('export.appointments.range');

    // Route::get('/mvola-test-form', function () {
    //     return view('mvola/index');
    // });

    Route::get('/success', function () {
        return view('mvola/success');
    });

    // Route::prefix('mvola-test')->group(function () {
    // Route::get('/env', [\App\Http\Controllers\MvolaTestController::class, 'checkEnv']);
    // Route::get('/correlation-id', [\App\Http\Controllers\MvolaTestController::class, 'generateCorrelationId']);
    // Route::get('/token', [\App\Http\Controllers\MvolaTestController::class, 'getToken']);
    // Route::post('/pay', [\App\Http\Controllers\MvolaTestController::class, 'testPaiement']);
    // });


});

