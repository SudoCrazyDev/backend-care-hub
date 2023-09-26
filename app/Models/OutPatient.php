<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OutPatient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'significant_findings',
        'medicines',
        'professional_fee',
        'amount_tendered',
        'status',
        'remarks'
    ];
    
    public function admission(): HasOne
    {
        return $this->hasOne(AdmissionForm::class);
    }
}
