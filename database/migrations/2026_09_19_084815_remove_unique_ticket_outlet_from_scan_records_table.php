<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scan_records', function (Blueprint $table) {
            // Pastikan masing-masing foreign key mempunyai index sendiri
            $table->index(
                'ticket_qrcode_id',
                'scan_records_ticket_qrcode_id_index'
            );

            $table->index(
                'outlet_id',
                'scan_records_outlet_id_index'
            );

            // Setelah foreign key memiliki index sendiri,
            // UNIQUE constraint bisa dihapus
            $table->dropUnique('scan_records_ticket_outlet_unique');

            // Tetap pertahankan composite index untuk query count()
            $table->index(
                ['ticket_qrcode_id', 'outlet_id'],
                'scan_records_ticket_outlet_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('scan_records', function (Blueprint $table) {
            $table->dropIndex('scan_records_ticket_outlet_index');

            $table->dropIndex('scan_records_ticket_qrcode_id_index');
            $table->dropIndex('scan_records_outlet_id_index');

            $table->unique(
                ['ticket_qrcode_id', 'outlet_id'],
                'scan_records_ticket_outlet_unique'
            );
        });
    }
};
