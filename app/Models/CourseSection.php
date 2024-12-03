<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; // UUID is not auto-incrementing
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function items()
    {
        return $this->hasMany(CourseItem::class);
    }
}
