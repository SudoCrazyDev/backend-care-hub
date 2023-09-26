<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineBrand;
use App\Models\MedicineUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicineController extends Controller
{
    public function get_all_medicines()
    {
        return Medicine::with(['brand', 'unit'])->get();
    }
    
    public function lookup_medicine($keyword)
    {
        return Medicine::with(['brand', 'unit'])->where('generic_name', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%')->get();
    }

    public function insert_medicine(Request $request)
    {
        Medicine::create($request->all());
        return Medicine::with(['brand', 'unit'])->paginate(10);
    }

    public function update_medicine(Request $request, $id)
    {
        return Medicine::find($id)->update($request->all());
    }
    public function get_medicine_brands()
    {
        return MedicineBrand::all();
    }
    
    public function lookup_medicine_brand($keyword)
    {
        return MedicineBrand::where('brand_name', 'like', '%'. $keyword. '%')->get();
    }
    
    public function get_medicine_units()
    {
        return MedicineUnit::all();
    }
    
    public function lookup_medicine_unit($keyword)
    {
        return MedicineUnit::where('unit_name', 'like', '%'. $keyword. '%')->get();
    }
    
    public function transfer_medicine()
    {
        DB::transaction(function () {
            Log::alert('Starting Old Units Transfer');
            $old_units = DB::table('tblunits')->get();
            foreach ($old_units as $old_unit){
                MedicineUnit::create([
                    'unit_name' => $old_unit->cUnitDesc,
                    'old_code' => $old_unit->nUnitID
                ]);
            }
            Log::alert('Old Units Transfer Complete');
            
            Log::alert('Starting Old Brands Transfer');
            $old_brands = DB::table('tblbrand')->get();
            foreach ($old_brands as $old_brand){
                MedicineBrand::create([
                    'brand_name' => $old_brand->cBrandName,
                    'old_code' => $old_brand->nBrandID
                ]);
            }
            Log::alert('Old Brands Transfer Complete');
            
            Log::alert('Old Medicine Transfer Start');
            $old_medicines = DB::table('tblitems')->get();
            foreach ($old_medicines as $old_medicine) {
                $new_brand = MedicineBrand::where('old_code', $old_medicine->nBrandID)->first();
                $new_unit = MedicineUnit::where('old_code', $old_medicine->nUnitID)->first();
                
                Medicine::create([
                        'brand_id' => $new_brand->id,
                        'unit_id' => $new_unit->id,
                        'generic_name' => $old_medicine->cGenericName,
                        'description' => $old_medicine->cItemDesc
                ]);
            }
            Log::alert('Old Medicine Transfer Complete');
        });
        return Medicine::all();
    }
}
