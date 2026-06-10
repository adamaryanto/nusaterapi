<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    protected $fillable = [
        'name',
        'price',
        'discount_wd',
        'discount_we',
        'limit_wd',
        'limit_we',
        'window',
        'duration',
        'status',
    ];

    /**
     * Get active tiers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get associated users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
