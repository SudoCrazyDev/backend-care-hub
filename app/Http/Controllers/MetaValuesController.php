<?php

namespace App\Http\Controllers;

use App\Models\MetaValues;
use Illuminate\Http\Request;

class MetaValuesController extends Controller
{
    public function get_meta_key_value($meta_key)
    {
        return MetaValues::where('meta_key', $meta_key)->get();
    }

    public function update_meta_key_value(Request $request, $id)
    {
        $meta = MetaValues::find($id);
        $meta->meta_values = $request->meta_values;
        $meta->save();
        return MetaValues::find($id);
    }
}
