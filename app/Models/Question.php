<?php

namespace App\Models;

use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    /**
     * @return array<string, string>
     */

    protected $fillable = [
        'content',
        'answer',
        'question_type',
        'quiz_id',
    ];

    protected function casts(): array
    {
        return [
            'question_type' => QuestionType::class,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return HasMany
     */
    public function questionChoices(): HasMany
    {
        return $this->hasMany(QuestionChoice::class);
    }

    /**
     * @return HasMany
     */
    public function quizSubmissionItems(): HasMany
    {
        return $this->hasMany(QuizSubmissionItem::class);
    }
}
