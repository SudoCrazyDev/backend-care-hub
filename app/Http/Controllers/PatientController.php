<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function get_patient_photo($id)
    {
        $patient = Patient::find($id);
        return $patient->getMedia()[0]->getUrl();
    }

    public function insert_patient(Request $request)
    {
        $patient = Patient::create($request->all());
        $patient->addMediaFromBase64($request->photo_url)->toMediaCollection();
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

    public function insert_patient_laboratory(Request $request)
    {
        $laboratory = DB::transaction(function () use ($request) {
            $files = $request['files'];
            $fileCount = count($files);
            $paths = [];

            $lab_request = json_decode($request->lab_request);

            $laboratory = Laboratory::create([
                'patient_id' => $request->patient_id,
                'cbc' => $lab_request->cbc,
                'urinalysis' => $lab_request->urinalysis,
                'stool_exam' => $lab_request->stool_exam,
                'blood_chemistry' => $lab_request->blood_chemistry,
                'xray' => $lab_request->xray,
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

    public function get_patient_laboratories($id)
    {
        return Laboratory::where('patient_id', $id)->get();
    }
}
