<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount', 'payment_method', 'transaction_id', 'status', 'paid_at','booking_id'
        ];
        protected $casts = [
            'paid_at' => 'datetime',
        ];
        public function booking()
        {
            return $this->belongsTo(Booking::class);
        }

        public function invoice()
        {
            return $this->hasOne(Invoice::class);
        }
}
