<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'specialty', 'avatar_path', 'rating', 'status'])]
class Therapist extends Model
{
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
