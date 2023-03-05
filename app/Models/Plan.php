<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillabe = [
        'slug',
        'price',
        'duration_in_days',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getvisualPriceAttribute()
    {
        return '$' . number_format($this->price / 100, 2, '.',',');
    }
}
