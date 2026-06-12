<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'id',
    'user_id',
    'therapist_id',
    'service_name',
    'schedule_date',
    'schedule_time',
    'duration',
    'location_type',
    'address',
    'service_price',
    'transport_price',
    'total_payment',
    'status',
    'pay_status',
    'rating',
    'review',
    'discount_amount',
    'is_membership_discount_applied',
    'reschedule_count',
    'reschedule_fee_charged',
    'admin_fee',
    'tax_amount'
])]
class Booking extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }
}
