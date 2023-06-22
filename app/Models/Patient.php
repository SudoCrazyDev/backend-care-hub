<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'address',
        'gender',
        'civil_status',
        'religion',
        'occupation',
        'contact_number',
        'patient_photo_url'
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function laboratory(): HasMany
    {
        return $this->hasMany(Laboratory::class);
    }
}
