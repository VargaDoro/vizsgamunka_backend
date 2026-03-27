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
            //'patientAppointments.patient'
            'patientAppointments.doctor' //beteg tudjon orvoshoz időpontot foglalni

        ]);
    });
});

// Betegnek szóló csoport
Route::middleware(['auth:sanctum', PatientMW::class])
    ->group(function () {
        //orvosok kilistázása funkcióhoz
        Route::get('/doctors', [DoctorController::class, 'index']);
        //csak egy orvos listázása
        Route::get('/doctors/{id}', [DoctorController::class, 'show']);
        //beteg időpontfoglalása orvoshoz
        Route::get('/doctors/{doctor_id}/appointments', [AppointmentController::class, 'doctorAppointmentsForPatient']);
        Route::post('/doctors/{doctor_id}/appointments', [AppointmentController::class, 'patientBookAppointment']);
        //beteg saját foglalt időpontjainak listázása
        Route::get('/appointments', [AppointmentController::class, 'patientIndex']);
        // beteg saját időpont törlése
        Route::delete('/appointments/{id}', [AppointmentController::class, 'patientDestroy']);
        // szakrendelések: elérhető szakterületek listája
        Route::get('/specializations', [DoctorController::class, 'specializations']);
    });
/*
// Admin funkciók
Route::middleware(['auth:sanctum', AdminMW::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    //orvosok kilistázása funkcióhoz
    Route::get('/doctors', [DoctorController::class, 'index']);
    // Összes beteg listázása admin számára
    Route::get('/patients', [PatientController::class, 'index']);
});
*/ 

//prefixes megoldás, hogy ne írja felül egymást, ha ugyanazt a route-ot akarjuk használni
Route::middleware(['auth:sanctum', AdminMW::class])
    ->prefix('admin')
    ->group(function () {

        // admin összes felhasználó listája
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);

        // admin orvosok listája (külön útvonal!)
        Route::get('/doctors', [DoctorController::class, 'index']);

        // admin összes beteg listája
        Route::get('/patients', [PatientController::class, 'index']);
    });

// Orvos funkciók
Route::middleware(['auth:sanctum', DoctorMW::class])
    ->prefix('doctor')
    ->group(function () {
        Route::get('/patients', [PatientController::class, 'doctorIndex']);
        Route::get('/patients/{id}', [PatientController::class, 'doctorShow']);
        // Dokumentum feltöltés
        Route::post('/documents', [DocumentController::class, 'doctorStore']);
        // Időpontok
        Route::get('/appointments', [AppointmentController::class, 'doctorIndex']);
        // Recept CRUD
        Route::apiResource('/prescriptions', PrescriptionController::class);
    });
