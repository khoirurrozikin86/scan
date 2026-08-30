<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketQrcode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_qrcodes';

    protected $fillable = [
        'no_tiket',
        'qrcode',
        'ticket_type',
        'remark',
    ];

    public function scanRecords()
    {
        return $this->hasMany(
            ScanRecord::class,
            'ticket_qrcode_id'
        );
    }
}
