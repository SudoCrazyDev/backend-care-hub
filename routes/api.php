<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MetaValuesController;
use App\Http\Controllers\OutPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\MetaValues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    Route::get('get_patient_admissions/{id}', [PatientController::class, 'get_patient_admissions']);
    Route::get('get_patient_latest_out_patient/{id}', [PatientController::class, 'get_patient_latest_out_patient']);
    Route::get('get_patient_last_medications/{id}', [PatientController::class, 'get_patient_last_medications']);
    Route::get('get_patient_medications/{id}', [PatientController::class, 'get_patient_medications']);
    Route::get('lookup/{keyword}', [PatientController::class, 'patient_lookup']);
    Route::post('insert_patient', [PatientController::class, 'insert_patient']);
    Route::post('insert_laboratory', [PatientController::class, 'insert_patient_laboratory']);
    Route::post('insert_laboratory_request', [PatientController::class, 'insert_laboratory_request']);
    Route::post('insert_admission', [PatientController::class, 'insert_patient_admission']);
    Route::post('insert_medicine', [PatientController::class, 'insert_patient_medicine']);
    Route::put('update_patient/{id}', [PatientController::class, 'update_patient']);
    Route::delete('delete_patient/{id}', [PatientController::class, 'delete_patient']);
});

Route::prefix('appointments')->group(function () {
    Route::get('get_all_appointments', [AppointmentController::class, 'get_all_appointments']);
    Route::get('get_appointment/{id}', [AppointmentController::class, 'get_appointment']);
    Route::get('get_appointment_by_patient/{id}', [AppointmentController::class, 'get_appointments_by_patient']);
    Route::get('get_appointments_by_date/{date}', [AppointmentController::class, 'get_appointments_by_date']);
    Route::get('get_appointment_lab_request/{id}', [AppointmentController::class, 'get_appointment_lab_request']);
    Route::get('get_appointment_out_patient/{id}', [AppointmentController::class, 'get_appointment_out_patient']);
    Route::post('insert_appointment', [AppointmentController::class, 'insert_appointment']);
    Route::put('update_appointment/{id}', [AppointmentController::class, 'update_appointment']);
    Route::put('cancel_appointment/{id}', [AppointmentController::class, 'cancel_appointment']);
});

Route::prefix('medicines')->group(function () {
    Route::get('get_all_medicines', [MedicineController::class, 'get_all_medicines']);
    Route::get('get_medicine_brands', [MedicineController::class, 'get_medicine_brands']);
    Route::get('get_medicine_units', [MedicineController::class, 'get_medicine_units']);
    Route::get('lookup_medicine/{keyword}', [MedicineController::class, 'lookup_medicine']);
    Route::post('insert_medicine', [MedicineController::class, 'insert_medicine']);
});

Route::prefix('out_patients')->group(function () {
    Route::get('get_all_outpatients', [OutPatientController::class, 'get_all_outpatients']);
    Route::get('get_outpatient/{id}', [OutPatientController::class, 'get_outpatient']);
    Route::post('reports', [OutPatientController::class, 'get_outpatient_report']);
    Route::post('billout', [OutPatientController::class, 'billout']);
    Route::post('insert_outpatient', [OutPatientController::class, 'insert_outpatient']);
    Route::put('update/{id}',[OutPatientController::class, 'update_outpatient']);
});

Route::prefix('meta_values')->group(function () {
    Route::get('get_meta_key_value/{meta_key}', [MetaValuesController::class, 'get_meta_key_value']);
    Route::put('update_meta_key_value/{id}', [MetaValuesController::class, 'update_meta_key_value']);
});

Route::post('file_upload', function (Request $request) {
    $laboratory = Laboratory::find($request->lab_request_id);
    $files = $request['files'];
    $fileCount = count($files);
    $paths = [];
    for ($i = 0; $i <= $fileCount - 1; $i++) {
        array_push($paths, Storage::putFile('public/laboratory_result/' . $laboratory->patient_id . '/' . $laboratory->id, $files[$i]));
    }
    $laboratory->status = 'complete';
    $laboratory->result_url = implode('::', $paths);
    $laboratory->result_date = $request->result_date;
    $laboratory->save();
    return Laboratory::find($request->lab_request_id);
});

Route::get('original_patients', [PatientController::class, 'transfer_old_patient_data']);
Route::get('original_medicines', [MedicineController::class, 'transfer_medicine']);