<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSubmissionItem extends Model
{
    /** @use HasFactory<\Database\Factories\QuizSubmissionItemFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    /**
     * @return BelongsTo
     */
    public function quizSubmission(): BelongsTo
    {
        return $this->belongsTo(QuizSubmission::class);
    }
}
