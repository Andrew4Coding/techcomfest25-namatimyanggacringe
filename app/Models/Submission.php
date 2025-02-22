<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Submission extends Model
{
    /** @use HasFactory<\Database\Factories\SubmissionFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'content',
        'opened_at',
        'due_date',
        'file_types',
        'max_attempts',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'due_date' => 'datetime',
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
    public function submissionItems(): HasMany
    {
        return $this->hasMany(SubmissionItem::class);
    }
}
