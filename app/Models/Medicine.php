<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'generic_name',
        'description',
        'unit_id',
        'brand_id'
    ];
    
    public function unit()
    {
        return $this->belongsTo(MedicineUnit::class);
    }
    
    public function brand()
    {
        return $this->belongsTo(MedicineBrand::class);
    }
}
