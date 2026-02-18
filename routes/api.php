<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMW;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//autentikált végpontok
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [UserController::class, 'show_auth']);
    // Kijelentkezés útvonal
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    $user = $request->user()->load([
        'doctorAppointments.doctor',  
        'patientAppointments.patient'  
    ]);
    return response()->json($user);
});

//admin végpontok
Route::middleware(['auth:sanctum', AdminMW::class])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
});

Route::get('/set-cookie', [UserController::class, 'setCookie']);
Route::get('/read-cookie', [UserController::class, 'readCookie']);
Route::get('/delete-cookie', [UserController::class, 'deleteCookie']);
//bárki által hozzáférhető útvonal
//Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']); // tokenes login
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthenticatedSessionController::class, 'store']);


//ha jol ertem akk kulon group-okba kell rakni minden kulon jogosultsagu felhasznalo szamara elerheto utvonalakat??

//////////////////////////      ÁTNÉZENDŐ     ////////////////////////////////// 
Route::middleware(['auth:sanctum', AdminMW::class])->group(function () {

    // Felhasználók CRUD            //crud az mi??
    Route::apiResource('users', UserController::class);

    // Orvosok kezelése
    Route::apiResource('doctors', DoctorController::class);

    // Páciensek kezelése
    Route::apiResource('patients', PatientController::class);

    // Időpontok kezelése
    Route::apiResource('appointments', AppointmentController::class);

    // Dokumentumok kezelése
    Route::apiResource('documents', DocumentController::class);

    // Dokumentumtípusok kezelése
    Route::apiResource('document-types', DocumentTypeController::class);

    // Receptek kezelése
    Route::apiResource('prescriptions', PrescriptionController::class);

    // Rendelőhelyek kezelése
    Route::apiResource('office-locations', OfficeLocationController::class);

});


 //6.4 Páciens-specifikus végpontok


Route::middleware(['auth:sanctum', PatientMW::class])->group(function () {

    // Saját időpontok
    Route::get('/appointments', [AppointmentController::class, 'index']);

    // Időpont foglalása
    Route::post('/appointments', [AppointmentController::class, 'store']);

    // Saját időpont törlése
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

    // Saját dokumentumok
    Route::get('/documents', [DocumentController::class, 'index']);

    // Saját receptek
    Route::get('/prescriptions', [PrescriptionController::class, 'index']);
});


// 6.5 Orvos-specifikus végpontok

Route::middleware(['auth:sanctum', DoctorMW::class])->group(function () {

    // Saját páciensek időpontjai
    Route::get('/appointments', [AppointmentController::class, 'index']);

    // Dokumentum feltöltés pácienshez
    Route::post('/documents', [DocumentController::class, 'store']);

    // Recept felírása
    Route::post('/prescriptions', [PrescriptionController::class, 'store']);
});

