<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'method',
        'url',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Model yang diaudit.
     *
     * Bisa berupa:
     * Ticket
     * Order
     * Payment
     * Product
     * User
     * dll.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan module.
     */
    public function scopeModule(
        Builder $query,
        string $module
    ): Builder {
        return $query->where(
            'module',
            $module
        );
    }

    /**
     * Filter berdasarkan action.
     */
    public function scopeAction(
        Builder $query,
        string $action
    ): Builder {
        return $query->where(
            'action',
            $action
        );
    }

    /**
     * Filter berdasarkan user.
     */
    public function scopeByUser(
        Builder $query,
        int $userId
    ): Builder {
        return $query->where(
            'user_id',
            $userId
        );
    }

    /**
     * Filter berdasarkan tanggal.
     */
    public function scopeBetweenDates(
        Builder $query,
        $from,
        $to
    ): Builder {
        return $query->whereBetween(
            'created_at',
            [
                $from,
                $to,
            ]
        );
    }
}
