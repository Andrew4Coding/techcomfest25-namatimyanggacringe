<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CourseItem extends Model
{
    /** @use HasFactory<\Database\Factories\CourseItemFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    public $fillable = [
        'name',
        'description',
        'isPublic',
        'course_section_id',
    ];

    /**
     * @return BelongsTo
     */
    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }

    /**
     * @return MorphTo
     */
    public function courseItemable(): MorphTo
    {
        return $this->morphTo();
    }
}
