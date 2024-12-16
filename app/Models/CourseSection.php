<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CourseSection extends Model
{
    //
    use HasUuids, HasFactory;

    /**
     * @var string
     */
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'is_public'
    ];

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

    /**
     * @return HasManyThrough
     */
    public function materials(): HasManyThrough
    {
        return $this->hasManyThrough(Material::class, CourseItem::class);
    }

    /**
     * @return HasManyThrough
     */
    public function submissions(): HasManyThrough
    {
        return $this->hasManyThrough(Submission::class, CourseItem::class);
    }

    /**
     * @return HasManyThrough
     */
    public function forums(): HasManyThrough
    {
        return $this->hasManyThrough(Forum::class, CourseItem::class);
    }

    /**
     * @return HasManyThrough
     */
    public function quizzes(): HasManyThrough
    {
        return $this->hasManyThrough(Quiz::class, CourseItem::class);
    }
}
