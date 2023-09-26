<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaValues extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'meta_key',
        'meta_values'
    ];
}
