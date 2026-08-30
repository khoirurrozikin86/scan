<?php

namespace Database\Seeders;

use App\Models\TicketQrcode;
use Illuminate\Database\Seeder;

class TicketQrcodeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 103501; $i <= 103520; $i++) {
            $data[] = [
                'no_tiket' => (string) $i,
                'qrcode' => (string) $i,
                'ticket_type' => 'Wahana Sepuasnya',
                'remark' => 'OK',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        TicketQrcode::insert($data);
    }
}
