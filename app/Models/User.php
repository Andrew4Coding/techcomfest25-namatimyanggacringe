<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'phone_number',
        'password',
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

    /**
     * Get related student
     *
     * @return HasOne
     */
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'id');
    }

    /**
     * Get related teacher
     *
     * @return HasOne
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'id');
    }

    /**
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->student()->exists();
    }

    /**
     * @return bool
     */
    public function isTeacher(): bool
    {
        return $this->teacher()->exists();
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
