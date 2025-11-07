<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'order_number',
        'quantity',
        'price',
        'total_amount',
        'payment_status',
        'va_number',
        'payment_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isExpired()
    {
        return $this->payment_status === 'expired' ||
            ($this->expired_at && now()->isAfter($this->expired_at));
    }
}
