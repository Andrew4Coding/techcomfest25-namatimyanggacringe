<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseItemProgress extends Model
{
    use HasUuids, HasFactory;

    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'course_item_id',
        'user_id',
        'is_completed',
    ];

    public function courseItem() {
        return $this->belongsTo(CourseItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
