<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseItem extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; // UUID is not auto-incrementing
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }
}
