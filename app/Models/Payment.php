<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'student_id',
        'course_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
    ];

    public function student(){
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
