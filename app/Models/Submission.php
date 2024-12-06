<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    /**
     * @return BelongsTo
     */
    public function courseItem(): BelongsTo
    {
        return $this->belongsTo(CourseItem::class);
    }

    /**
     * @return HasMany
     */
    public function submissionItems(): HasMany
    {
        return $this->hasMany(SubmissionItem::class);
    }
}
