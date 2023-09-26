<?php

namespace App\Http\Controllers;

use App\Models\AdmissionForm;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function insert_admission(Request $request)
    {
        AdmissionForm::create([
            'patient_id' => $request->patient_id,
            'content' => $request->admission_content
        ]);
        return AdmissionForm::where('patient_id', $request->patient_id);
    }
}
