<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanRecord extends Model
{
    protected $fillable = [
        'user_id',
        'outlet_id',
        'ticket_qrcode_id',
        'qrcode',
        'no_tiket',
        'ticket_type',
        'scan_method',
        'scanned_at',
        'remark',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function ticketQrcode(): BelongsTo
    {
        return $this->belongsTo(
            TicketQrcode::class,
            'ticket_qrcode_id'
        );
    }
}
