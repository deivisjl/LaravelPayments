<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $primaryKey = 'iso';
    protected $incrementing = false;

    use HasFactory;

    protected $fillable = [
        'iso',
    ];
}
