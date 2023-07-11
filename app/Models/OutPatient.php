<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutPatient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'appointment_id',
        'significant_findings',
        'medicines',
        'professional_fee'
    ];
}
