<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashCardItem extends Model
{
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'question',
        'is_public',
        'answer',
    ];

    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}
