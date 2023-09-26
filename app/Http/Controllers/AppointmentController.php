<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Laboratory;
use App\Models\OutPatient;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function get_all_appointments()
    {
        return Appointment::with(['laboratory', 'patient'])->get();
    }

    public function get_appointments_by_date($date)
    {
        return Appointment::with(['laboratory', 'patient'])->where('consultation_date', Carbon::parse($date))->get();
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
        return Appointment::with(['laboratory', 'patient'])->where('consultation_date', Carbon::parse($request->current_date))->get();
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
                'form_details' => $lab_request,
                'status' => 'Waiting for Result',
                'type' => 'Request'
            ]);

            //Update the appointment add the laboratory;
            $appointment->lab_request_id = $laboratory->id;
            $appointment->save();
        }

        if($request->has_medications){
            OutPatient::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $request->patient_id,
                'medicines' => $request->medications
            ]);
        }
        //Return all appointment and Laboratories.
        return Patient::with(['appointments.laboratory', 'appointments.patient'])->find($request->patient_id);
    }

    public function get_appointment_lab_request($id)
    {
        $appointment = Appointment::find($id);
        return Laboratory::findOrFail($appointment->lab_request_id);
    }

    public function get_appointment_out_patient($id)
    {
        return OutPatient::with('admission')->where('appointment_id', $id)->get();
    }

    public function update_appointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->blood_pressure = $request->blood_pressure;
        $appointment->heart_rate = $request->heart_rate;
        $appointment->temperature = $request->temperature;
        $appointment->weight = $request->weight;
        $appointment->chief_complaint = $request->chief_complaint;
        $appointment->save();
        return Appointment::with(['laboratory', 'patient'])->where('consultation_date', Carbon::parse($appointment->consultation_date))->get();
    }
}
