<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSection extends Model
{
    //
    use HasUuids, HasFactory;

    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return HasMany
     */
    public function courseItems(): HasMany
    {
        return $this->hasMany(CourseItem::class);
    }
}
