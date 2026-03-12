<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMW;
use App\Http\Middleware\DoctorMW;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Middleware\PatientMW;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', function (Request $request) {
        return $request->user()->load([
            'doctorAppointments.doctor',
            'patientAppointments.patient'
        ]);
    });

});

// Betegnek szóló csoport (orvosok kilistázása funkcióhoz)
Route::middleware(['auth:sanctum', PatientMW::class])
    ->group(function () {
        Route::get('/doctors', [DoctorController::class, 'index']);
    });

// Admin funkciók
Route::middleware(['auth:sanctum', AdminMW::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Összes beteg listázása admin számára
    Route::get('/patients', [PatientController::class, 'index']);
});

//Orvos funkciók
Route::middleware(['auth:sanctum', DoctorMW::class])
    ->prefix('doctor')
    ->group(function () {
        // Saját páciensek listázása
        Route::get('/patients', [PatientController::class, 'doctorIndex']);
        // Egy páciens lekérdezése
        Route::get('/patients/{patient_id}', [PatientController::class, 'doctorShow']);
        // Dokumentum feltöltés
        Route::post('/documents', [DocumentController::class, 'doctorStore']);
        // Időpont CRUD
        Route::get('appointments', [AppointmentController::class, 'doctorIndex']); //post,put,delete
        // Recept CRUD
        Route::apiResource('prescriptions', PrescriptionController::class); //get,post,put,delete külön doctor...
    });


