<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OutPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [UserController::class, 'login']);

Route::prefix('patients')->group(function () {
    Route::get('get_patients', [PatientController::class, 'get_all_patients']);
    Route::get('get_patient/{id}', [PatientController::class, 'get_patient']);
    Route::get('get_patient_photo/{id}', [PatientController::class, 'get_patient_photo']);
    Route::get('get_patient_laboratories/{id}', [PatientController::class, 'get_patient_laboratories']);
    Route::get('lookup/{keyword}', [PatientController::class, 'patient_lookup']);
    Route::post('insert_patient', [PatientController::class, 'insert_patient']);
    Route::post('insert_laboratory', [PatientController::class, 'insert_patient_laboratory']);
    Route::put('update_patient/{id}', [PatientController::class, 'update_patient']);
    Route::delete('delete_patient/{id}', [PatientController::class, 'delete_patient']);
});

Route::prefix('appointments')->group(function () {
    Route::get('get_all_appointments', [AppointmentController::class, 'get_all_appointments']);
    Route::get('get_appointment/{id}', [AppointmentController::class, 'get_appointment']);
    Route::get('get_appointment_by_patient/{id}', [AppointmentController::class, 'get_appointments_by_patient']);
    Route::get('get_appointment_lab_request/{id}', [AppointmentController::class, 'get_appointment_lab_request']);
    Route::get('get_appointment_out_patient/{id}', [AppointmentController::class, 'get_appointment_out_patient']);
    Route::post('insert_appointment', [AppointmentController::class, 'insert_appointment']);
    Route::put('update_appointment/{id}', [AppointmentController::class, 'update_appointment']);
    Route::put('cancel_appointment/{id}', [AppointmentController::class, 'cancel_appointment']);
});

Route::prefix('medicines')->group(function () {
    Route::get('get_all_medicines', [MedicineController::class, 'get_all_medicines']);
    Route::post('insert_medicine', [MedicineController::class, 'insert_medicine']);
});

Route::prefix('out_patients')->group(function () {
    Route::get('get_all_outpatients', [OutPatientController::class, 'get_all_outpatients']);
    Route::get('get_outpatient/{id}', [OutPatientController::class, 'get_outpatient']);
    Route::post('insert_outpatient', [OutPatientController::class, 'insert_outpatient']);
});



Route::post('file_upload', function (Request $request) {
    $path = $request->file('file')->store('public');
    $laboratory = Laboratory::find($request->lab_request_id);
    $laboratory->result_url = $path;
    $laboratory->status = 'completed';
    $laboratory->save();
    return $laboratory;
});
