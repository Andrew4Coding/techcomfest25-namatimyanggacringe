<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Quiz extends Model
{
    /** @use HasFactory<\Database\Factories\QuizFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'start',
        'finish',
        'duration',
    ];

    /**
     * @return MorphOne
     */
    public function courseItem(): MorphOne
    {
        return $this->morphOne(CourseItem::class, 'course_itemable');
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * @return HasMany
     */
    public function quizSubmissions(): HasMany
    {
        return $this->hasMany(QuizSubmission::class);
    }
}
