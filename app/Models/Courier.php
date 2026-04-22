<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
        'description'
    ];
}
