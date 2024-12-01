<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'subject_name',
        'description',
        'teacher_id',
    ];

    // Relationship: A subject is taught by one teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
