<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; // UUID is not auto-incrementing
    protected $keyType = 'string'; // UUID is a string

    protected $fillable = ['name', 'description', 'code'];

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }
}
