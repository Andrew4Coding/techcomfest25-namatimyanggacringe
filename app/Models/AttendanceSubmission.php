<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttendanceSubmission extends Model
{
    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'attendance_id',
        'student_id',
        'status',
    ];

    // Belongs to Attendance
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    // Belongs to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }
}
