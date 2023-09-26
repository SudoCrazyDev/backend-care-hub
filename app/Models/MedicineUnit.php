<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineUnit extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'unit_name',
        'old_code'
    ];
}
