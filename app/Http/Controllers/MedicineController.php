<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function get_all_medicines()
    {
        return Medicine::all();
    }

    public function insert_medicine(Request $request)
    {
        Medicine::create($request->all());
        return Medicine::all();
    }
}
