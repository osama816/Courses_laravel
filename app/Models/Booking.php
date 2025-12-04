<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

        protected $fillable = [
        'status',
        'user_id',
        'course_id',
        'payment_method'
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(course::class);
    }
    public function payment()
{
    return $this->hasOne(\App\Models\Payment::class, 'booking_id');
}

}
