<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMW;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DocumentController;


//autentikált végpontok
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'show_auth']);
    // Kijelentkezés útvonal
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

//admin végpontok
Route::middleware(['auth:sanctum', AdminMW::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
});

//bárki által hozzáférhető útvonal
//Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

/////PATIENT////

// Bejelentkezett páciens saját adatai (PatientHomePage-nek)
    Route::get('/patient/me', [PatientController::class, 'me']);

    // Páciens időpontjai + foglalás
    Route::get('/appointments/me', [AppointmentController::class, 'myAppointments']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'cancel']);

    // Páciens dokumentumai
    Route::get('/documents/me', [DocumentController::class, 'myDocuments']);
    Route::get('/documents/{document}', [DocumentController::class, 'show']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);

