<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function get_all_patients()
    {
        return Patient::with('appointments')->paginate(50);
    }

    public function patient_lookup($patient_keyword)
    {
        return Patient::where('firstname', 'LIKE', '%' . $patient_keyword . '%')
            ->orWhere('lastname', 'LIKE', '%' . $patient_keyword . '%')
            ->get();
    }

    public function get_patient($id)
    {
        $patient = Patient::find($id);
        return $patient;
    }

    public function insert_patient(Request $request)
    {
        $patient = Patient::create($request->all());
        return $patient;
    }

    public function update_patient(Request $request, $id)
    {
        $patient = Patient::find($id);
        $patient->update($request->all());
        return $patient;
    }

    public function delete_patient($id)
    {
        $patient = Patient::find($id);
        $patient->delete();
        return Patient::all();
    }
}
