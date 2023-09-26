<?php

namespace App\Http\Controllers;

use App\Models\AdmissionForm;
use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\OutPatient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutPatientController extends Controller
{
    public function get_all_outpatients()
    {
        return OutPatient::all();
    }

    public function get_outpatient($id)
    {
        return OutPatient::with('admission')->where('id', $id)->get();
    }

    public function insert_outpatient(Request $request)
    {
        DB::transaction(function () use ($request) {
            $appointment = Appointment::find($request->appointment_id);
            $appointment->status = "Waiting for Billing";
            $lab_request = $request->lab_request;
            
            if ($request->has_lab_request && !$appointment->has_lab_request) {
                $laboratory = Laboratory::create([
                    'appointment_id' => $appointment->id,
                    'patient_id' => $request->patient_id,
                    'form_details' => $lab_request,
                    'status' => 'Waiting for Result',
                    'type' => 'Request'
                ]);
                $appointment->has_lab_request = 1;
                $appointment->lab_request_id = $laboratory->id;
            }

            $out_patient = OutPatient::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $request->patient_id,
                'significant_findings' => $request->significant_findings,
                'medicines' => $request->medications,
                'professional_fee' => $request->professional_fee,
                'remarks' => $request->remarks
            ]);

            if($request->has_admission){
                AdmissionForm::create([
                    'patient_id' => $request->patient_id,
                    'out_patient_id' => $out_patient->id,
                    'content' => $request->admission_content
                ]);
            }
            $appointment->save();
            return true;
        });

        return Appointment::with(['laboratory', 'patient'])->where('consultation_date', Carbon::parse($request->current_date))->get();
    }
    
    public function billout(Request $request)
    {
        $out_patient = OutPatient::find($request->out_patient_id);
        $out_patient->amount_tendered = $request->amount_tendered;
        $out_patient->save();
        $appointment = Appointment::find($request->appointment_id);
        $appointment->status = 'complete';
        $appointment->save();
        return Appointment::with(['laboratory', 'patient'])->where('consultation_date', Carbon::parse($request->current_date))->get();
    }
}
