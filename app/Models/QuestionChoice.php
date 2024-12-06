<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionChoice extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionChoiceFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    /**
     * @return BelongsTo
     */

     protected $fillable = [
        'content',
        'question_id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
