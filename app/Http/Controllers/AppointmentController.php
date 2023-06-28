<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function get_all_appointments()
    {
        return Appointment::with(['laboratory', 'patient'])->get();
    }

    public function get_appointment($id)
    {
        return Appointment::findOrFail($id);
    }

    public function get_appointments_by_patient($id)
    {
        return Patient::with(['appointments.laboratory', 'appointments.patient'])->find($id);
    }

    public function cancel_appointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();
        return Patient::with(['appointments.laboratory', 'appointments.patient'])->find($request->patient_id);
    }

    public function insert_appointment(Request $request)
    {
        $lab_request = $request->lab_request;
        //Create the appointment first.
        $appointment = Appointment::create($request->all());

        if ($request->has_lab_request) {
            //Then Create the Laboratory Request.
            $laboratory = Laboratory::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $request->patient_id,
                'cbc' => $lab_request['cbc'],
                'urinalysis' => $lab_request['urinalysis'],
                'stool_exam' => $lab_request['stool_exam'],
                'blood_chemistry' => $lab_request['blood_chemistry'],
                'xray' => $lab_request['xray'],
                'status' => 'Waiting for Result',
                'type' => 'Request',
            ]);

            //Update the appointment add the laboratory;
            $appointment->lab_request_id = $laboratory->id;
            $appointment->save();
        }

        //Return all appointment and Laboratories.
        return Patient::with(['appointments.laboratory', 'appointments.patient'])->find($request->patient_id);
    }

    public function get_appointment_lab_request($id)
    {
        $appointment = Appointment::findOrFail($id);
        return Laboratory::findOrFail($appointment->lab_request_id);
    }
}
