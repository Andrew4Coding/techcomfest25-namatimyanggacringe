<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumReply extends Model
{
    /** @use HasFactory<\Database\Factories\ForumReplyFactory> */
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    /**
     * @return BelongsTo
     */
    public function forum_discussion(): BelongsTo
    {
        return $this->belongsTo(ForumDiscussion::class);
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class);
    }
}
