<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'name',
        'email',
        'experience_years',
    ];

    // Relationship: A teacher can teach many subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
