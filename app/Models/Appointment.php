<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'consultation_date',
        'patient_id',
        'blood_pressure',
        'weight',
        'heart_rate',
        'temperature',
        'chief_complaint',
        'has_lab_request',
        'lab_request_id',
        'status',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function laboratory(): HasOne
    {
        return $this->hasOne(Laboratory::class);
    }
}
