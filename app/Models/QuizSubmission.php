<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSubmission extends Model
{
    /** @use HasFactory<\Database\Factories\QuizSubmissionFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'quiz_id',
        'student_id',
    ];

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return HasMany
     */
    public function quizSubmissionItems(): HasMany
    {
        return $this->hasMany(QuizSubmissionItem::class);
    }
}
