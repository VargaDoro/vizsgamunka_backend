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
        //beteg saját dokumentumai
        Route::get('/documents', [DocumentController::class, 'patientIndex']);
    });

// Orvos funkciók
Route::middleware(['auth:sanctum', DoctorMW::class])
    ->prefix('doctor')
    ->group(function () {
        Route::get('/patients', [PatientController::class, 'doctorIndex']);
        Route::post('/patients', [PatientController::class, 'doctorStore']);
        Route::get('/patients/{id}', [PatientController::class, 'doctorShow']);
        // Dokumentum feltöltés
        Route::get('/document_types', [DocumentController::class, 'documentTypes']);
        Route::get('/document_type', [DocumentController::class, 'documentTypes']);
        Route::post('/documents', [DocumentController::class, 'upload']);
        // Időpontok
        Route::get('/appointments', [AppointmentController::class, 'doctorIndex']);
    });