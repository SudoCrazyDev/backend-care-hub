<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\OutPatient;
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
        return OutPatient::findOrFail($id);
    }

    public function insert_outpatient(Request $request)
    {
        DB::transaction(function () use ($request) {
            $appointment = Appointment::find($request->appointment_id);
            $appointment->status = "complete";

            if ($request->has_lab_request && !$appointment->has_lab_request) {
                $laboratory = Laboratory::create([
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'cbc' => $request->lab_request['cbc'],
                    'urinalysis' => $request->lab_request['urinalysis'],
                    'stool_exam' => $request->lab_request['stool_exam'],
                    'blood_chemistry' => json_encode($request->lab_request['blood_chemistry']),
                    'xray' => json_encode($request->lab_request['xray']),
                    'status' => 'Waiting for Result',
                    'type' => 'Request',
                ]);
                $appointment->has_lab_request = 1;
                $appointment->lab_request_id = $laboratory->id;
            }

            OutPatient::create([
                'appointment_id' => $appointment->id,
                'significant_findings' => $request->significant_findings,
                'medicines' => $request->parsed_medicines,
                'professional_fee' => $request->professional_fee
            ]);

            $appointment->save();
            return true;
        });

        return Appointment::all();
    }
}
