<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'item_type',
        'item_id',
        'order_number',
        'thumbnail',
    ];

    // 🔗 Relationships
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }
}
