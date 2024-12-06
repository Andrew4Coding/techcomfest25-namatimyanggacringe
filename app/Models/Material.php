<?php

namespace App\Models;

use App\Enums\MaterialType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    /** @use HasFactory<\Database\Factories\MaterialFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MaterialType::class,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function courseItem(): BelongsTo {
        return $this->belongsTo(CourseItem::class);
    }
}
