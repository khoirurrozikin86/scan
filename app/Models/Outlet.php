<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Outlet extends Model
{



    protected $table = 'outlets';
    protected $fillable = [
        'outlet_code',
        'outlet_name',
        'outlet_type',
        'is_active',
        'is_camera_enabled',
        'is_scanner_enabled',
        'remark',
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'is_camera_enabled' => 'boolean',
        'is_scanner_enabled' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_outlets',
            'outlet_id',
            'user_id'
        );
    }

    public function scanRecords()
    {
        return $this->hasMany(
            ScanRecord::class
        );
    }
}
