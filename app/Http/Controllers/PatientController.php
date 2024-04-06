<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\AdmissionForm;
use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\OutPatient;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function get_all_patients()
    {
        return PatientResource::collection(Patient::with('appointments')->paginate(10));
    }

    public function patient_lookup($patient_keyword)
    {
        return
        PatientResource::collection( Patient::where('firstname', 'LIKE', '%' . $patient_keyword . '%')
        ->orWhere('lastname', 'LIKE', '%' . $patient_keyword . '%')
        ->get());
        ;
    }

    public function get_patient($id)
    {
        $patient = Patient::find($id);
        return $patient;
    }

    public function get_patient_photo($id)
    {
        $patient = Patient::find($id);
        return $patient->getMedia()[0]->getUrl();
    }

    public function insert_patient(Request $request)
    {
        DB::transaction(function() use($request){
            $patient = Patient::create($request->all());
            if($request->photo_url){
                $patient->addMediaFromBase64($request->photo_url)->toMediaCollection();
            }
        });
        return PatientResource::collection(Patient::with('appointments')->paginate(10));
    }

    public function update_patient(Request $request, $id)
    {
        DB::transaction(function() use($request, $id){
            $patient = Patient::find($id);
            $patient->update($request->all());
            $mediaItems = $patient->getMedia();
            if(count($mediaItems) > 0){
                $mediaItems[0]->delete();   
            }
            $patient->addMediaFromBase64($request->photo_url)->toMediaCollection();
        });
        return PatientResource::collection(Patient::with('appointments')->paginate(10));
    }

    public function delete_patient($id)
    {
        $patient = Patient::find($id);
        $patient->delete();
        return Patient::all();
    }

    public function insert_patient_laboratory(Request $request)
    {
        $laboratory = DB::transaction(function () use ($request) {
            $files = $request['files'];
            $fileCount = count($files);
            $paths = [];

            $laboratory = Laboratory::create([
                'patient_id' => $request->patient_id,
                'form_details' => $request->lab_request,
                'status' => 'complete',
                'type' => 'Result',
            ]);

            for ($i = 0; $i <= $fileCount - 1; $i++) {
                array_push($paths, Storage::putFile('public/laboratory_result/' . $request->patient_id . '/' . $laboratory->id, $files[$i]));
            }

            $laboratory->result_url = implode('::', $paths);
            $laboratory->result_date = $request->result_date;
            $laboratory->save();

            return Laboratory::where('patient_id', $request->patient_id)->get();
        });

        return $laboratory;
    }
    
    public function insert_laboratory_request(Request $request){
        $lab_request = $request->lab_request;
        Laboratory::create([
            'patient_id' => $request->patient_id,
            'form_details' => $lab_request,
            'status' => 'Waiting for Result',
            'type' => 'Request'
        ]);
        return Laboratory::where('patient_id', $request->patient_id)->get();
    }

    public function get_patient_laboratories($id)
    {
        return Laboratory::where('patient_id', $id)->get();
    }
    
    public function get_patient_admissions($id)
    {
        return AdmissionForm::where('patient_id', $id)->get();
    }

    public function insert_patient_admission(Request $request)
    {
        AdmissionForm::create([
            'patient_id' => $request->patient_id,
            'content' => $request->content
        ]);
        
        return AdmissionForm::where('patient_id', $request->patient_id)->get();
    }
    
    public function transfer_old_patient_data()
    {
        ini_set('max_execution_time', 3600);
        DB::transaction(function () {
            $original_patients = DB::table('tblpatient')->get();

            foreach ($original_patients as $patient) {
                $name_count = count(explode(",", $patient->cPatientName));

                if ($name_count === 1) {
                    $firstname = explode(",", $patient->cPatientName)[0];
                    $lastname = '';
                } else {
                    $firstname = explode(",", $patient->cPatientName)[1];
                    $lastname = explode(",", $patient->cPatientName)[0];
                }

                $civil_status = 'SINGLE';

                switch ($patient->cCivilStatus) {
                    case 1;
                        $civil_status = 'SINGLE';
                        break;
                    case 2;
                        $civil_status = 'MARRIED';
                        break;
                    case 3;
                        $civil_status = 'SEPARATED';
                        break;
                    case 4;
                        $civil_status = 'NOT STATED';
                        break;
                    case 5;
                        $civil_status = 'SINGLE PARENT';
                        break;
                    case 6;
                        $civil_status = 'WIDOW/ WIDOWER';
                        break;
                    case 7;
                        $civil_status = 'CHILD';
                        break;
                    default:
                        $civil_status = 'SINGLE';
                }

                //Insert to Patients Table
                $new_patient = Patient::create([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'birthdate' => Carbon::parse($patient->dBirthDate),
                    'address' => $patient->cAddress,
                    'gender' => $patient->cSex,
                    'civil_status' => $civil_status,
                    'religion' => $patient->cReligion,
                    'occupation' => $patient->cOccupation,
                    'contact_number' => $patient->cContactNos
                ]);

                $patient_appointments = DB::table('tbloutpatient')->where('nPatientID', $patient->nPatientID)->get();
                if (count($patient_appointments) !== 0) {
                    foreach ($patient_appointments as $appointment) {

                        //Check for Appointment Details (This should be inserted in appointments)
                        $appointment_details = DB::table('tbloutpatientdet')->where('nOutPatientHID', $appointment->nOutPatientHID)->get();

                        if (count($appointment_details) >= 1) {
                            $new_appointment = Appointment::create([
                                'patient_id' => $new_patient->id,
                                'consultation_date' => $appointment->dConsultationDate == '0000-00-00' ? null : $appointment->dConsultationDate,
                                'blood_pressure' => $appointment_details[0]->cBP,
                                'weight' => $appointment_details[0]->cWeight,
                                'heart_rate' => $appointment_details[0]->cHeartRate,
                                'temperature' => $appointment_details[0]->cTemperature,
                                'chief_complaint' => $appointment_details[0]->cChiefComplaints,
                                'status' => 'complete'
                            ]);

                            OutPatient::create([
                                'patient_id' => $new_patient->id,
                                'appointment_id' => $new_appointment->id,
                                'significant_findings' => $appointment->cSignificantFindings,
                                'professional_fee' => $appointment->nProfessionalFee
                            ]);
                        }
                    }
                }
                
                //INSERT LAST MEDICATIONS
                $patient_last_medication_list = DB::table('tblhomeinstructions')->where('nPatientID', $patient->nPatientID)->latest('dDateIssued')->first();
                if($patient_last_medication_list){
                    $patient_last_medications = DB::table('tblhomeinstructionsdet')->where('nHomeInsHID', $patient_last_medication_list->nHomeInsHID)->get();
                    $to_parsed_medicines = [];
                    if(count($patient_last_medications) !== 0){
                        foreach($patient_last_medications as $patient_last_medication){
                            array_push($to_parsed_medicines, [
                                'id' => '----',
                                'generic_name' => $patient_last_medication->xcGenericName,
                                'description' => $patient_last_medication->xcItemDesc,
                                'unit' => $patient_last_medication->xcUnitDesc,
                                'qty' => $patient_last_medication->nQty,
                                'instruction' => $patient_last_medication->cInstruction
                            ]);
                        }
                        OutPatient::create([
                            'patient_id' => $new_patient->id,
                            'medicines' => json_encode($to_parsed_medicines)
                        ]);
                    }
                }
                
                //INSERT PHOTO
                $patient_has_photo = file_exists('D:\Pictures' . "\\" . $patient->cPatientCode . '.jpg');
                if($patient_has_photo){
                    $new_patient->addMedia('D:\Pictures' . "\\" . $patient->cPatientCode . '.jpg')->toMediaCollection();
                }
                
            }
        });
        return Patient::all();
    }
    
    public function get_patient_latest_out_patient($id)
    {
        return OutPatient::where('patient_id', $id)->first();
    }
    
    public function get_patient_last_medications($id)
    {
        return OutPatient::where('patient_id', $id)
                        ->where('medicines', '!=', '')
                        ->latest()
                        ->first();
    }
    
    public function get_patient_medications($id)
    {
        return OutPatient::where('patient_id', '=', $id)
                          ->where('medicines', '!=', '')
                          ->get();
    }
    
    public function insert_patient_medicine(Request $request)
    {
        OutPatient::create([
            'patient_id' => $request->patient_id,
            'medicines' => $request->medications
        ]);
        
        return OutPatient::where('patient_id', '=', $request->patient_id)
                ->where('medicines', '!=', '')
                ->get();
    }
}
