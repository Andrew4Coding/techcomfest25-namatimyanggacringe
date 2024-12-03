<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends CourseItem
{
    use HasFactory, HasUuids;

    public $incrementing = false; // UUID is not auto-incrementing
    protected $keyType = 'string';

    protected $fillable = [
        'url',
    ];
}
