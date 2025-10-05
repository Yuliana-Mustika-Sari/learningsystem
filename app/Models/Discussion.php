<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'content',
        'parent_post_id',
    ];

    // 🔗 Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Discussion::class, 'parent_post_id');
    }

    public function replies()
    {
        return $this->hasMany(Discussion::class, 'parent_post_id');
    }
}
