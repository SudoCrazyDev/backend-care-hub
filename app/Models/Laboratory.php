<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboratory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'result_date',
        'cbc',
        'urinalysis',
        'stool_exam',
        'blood_chemistry',
        'xray',
        'others',
        'status',
        'type',
        'result_url',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
