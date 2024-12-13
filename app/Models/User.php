<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids, HasFactory, Notifiable;

    /**
     * Disable incrementing integer primary key
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * PK data type
     *
     * @var string
     */
    public $keyType = 'string';

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'verified',
        'phone_number',
        'password',
        'userable_id',
        'userable_type',
        'profile_picture',
    ];

    /**
     * The attributes that should be protected for serialization
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attribute that should be cast
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function forums(): HasMany
    {
        return $this->hasMany(Forum::class, 'creator_id');
    }

    /**
     * @return HasMany
     */
    public function forumDiscussions(): HasMany
    {
        return $this->hasMany(ForumDiscussion::class, 'creator_id');
    }

    /**
     * @return HasMany
     */
    public function forumReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'sender_id');
    }
}
