<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Attendance extends Model
{
    use HasUuids, HasFactory;

    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'password',
        'valid_until',
    ];

    // Has Many Attendance submission
    public function submissions()
    {
        return $this->hasMany(AttendanceSubmission::class);
    }

    public function courseItem(): MorphOne
    {
        return $this->morphOne(CourseItem::class, 'course_itemable');
    }
}
