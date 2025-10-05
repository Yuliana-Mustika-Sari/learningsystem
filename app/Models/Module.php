<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'content_type',
        'status',
    ];

    // 🔗 Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }
}
