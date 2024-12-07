<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasUuids, HasFactory;

    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    protected $hidden = [
        'pivot'
    ];

    /**
     * @return MorphOne
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * @return BelongsToMany
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * @return HasMany
     */
    public function submissionItems(): HasMany
    {
        return $this->hasMany(SubmissionItem::class);
    }

    /**
     * @return HasMany
     */
    public function quizSubmissions(): HasMany
    {
        return $this->hasMany(QuizSubmission::class);
    }
}
