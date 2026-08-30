<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{

 

  protected $table = 'outlets';
    protected $fillable = [
        'outlet_code',
        'outlet_name',
        'outlet_type',
        'is_active',
    ];


      protected $casts = [
        'is_active' => 'boolean',
    ];

  
}
