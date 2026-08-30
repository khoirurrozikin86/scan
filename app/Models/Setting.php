<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    protected $casts = ['value' => 'array'];


    public static function v(string $key, mixed $default = null): mixed
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }
}
