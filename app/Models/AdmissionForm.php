<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionForm extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'patient_id',
        'out_patient_id',
        'content'
    ];
    
    public function patient()
    {
        $this->belongsTo(Patient::class);
    }
}
