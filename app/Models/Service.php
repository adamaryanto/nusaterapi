<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_path',
        'default_duration',
        'price_clinic',
        'price_home',
        'description',
        'status',
        'sort_order',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active')->orderBy('sort_order');
    }
}
