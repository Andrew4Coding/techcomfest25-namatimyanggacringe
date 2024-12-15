<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    //
    use HasUuids, HasFactory;

    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    protected $hidden = [
        'pivot'
    ];

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'class_code',
        'subject'
    ];

    /**
     * @return BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * @return HasMany
     */
    public function courseSections(): HasMany
    {
        return $this->hasMany(CourseSection::class);
    }
}
