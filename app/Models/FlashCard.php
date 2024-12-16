<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashCard extends Model
{
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'description',
        'subject',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function FlashCardItem()
    {
        return $this->hasMany(FlashCardItem::class);
    }
}
