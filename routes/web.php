<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\AppointmentdbController;
use App\Http\Controllers\SessiondbController;
use App\Http\Controllers\ServicedbController;
use App\Http\Controllers\ServiceCategorydbController;

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
    Route::get('/appointmentsdb', [AppointmentdbController::class, 'index'])->name('appointmentsdb');
    Route::get('/session', [SessiondbController::class, 'index'])->name('sessiondb');
    Route::get('/session/create', [SessiondbController::class, 'create'])->name('sessiondb.create');
    Route::post('/session/store', [SessiondbController::class, 'store'])->name('sessiondb.store');
    Route::get('/services', [ServicedbController::class, 'index'])->name('servicedb');
    Route::get('/services/create', [ServicedbController::class, 'create'])->name('servicedb.create');
    Route::post('/services/store', [ServicedbController::class, 'store'])->name('servicedb.store');
    Route::get('/services/edit/{id}', [ServicedbController::class, 'edit'])->name('servicedb.edit');
    Route::patch('/services/update', [ServicedbController::class, 'update'])->name('servicedb.update');
    Route::get('/service-category', [ServiceCategorydbController::class, 'index'])->name('service-categorydb');
    Route::get('/service-category/create', [ServiceCategorydbController::class, 'create'])->name('service-categorydb.create');
    Route::post('/service-category/store', [ServiceCategorydbController::class, 'store'])->name('service-categorydb.store');
    Route::get('/service-category/edit/{id}', [ServiceCategorydbController::class, 'edit'])->name('service-categorydb.edit');
    Route::patch('/service-category/update', [ServiceCategorydbController::class, 'update'])->name('service-categorydb.update');
});
