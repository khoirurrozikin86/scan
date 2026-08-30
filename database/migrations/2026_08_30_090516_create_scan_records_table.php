<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scan_records', function (Blueprint $table) {
            $table->id();

            // User/operator yang melakukan scan
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Outlet tempat tiket discan
            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            // Tiket QR Code
            $table->foreignId('ticket_qrcode_id')
                ->constrained('ticket_qrcodes')
                ->restrictOnDelete();

            // Snapshot data tiket saat scan
            $table->string('no_tiket', 100);
            $table->string('qrcode', 255);

            // Jenis tiket
            $table->string('ticket_type', 100)->nullable();

            // Metode scan
            $table->enum('scan_method', [
                'camera',
                'scanner',
            ]);

            // Waktu tiket berhasil discan
            $table->timestamp('scanned_at')
                ->useCurrent();

            // Catatan tambahan
            $table->text('remark')
                ->nullable();

            $table->timestamps();

            /*
             * Satu tiket hanya boleh berhasil discan satu kali.
             */
            $table->unique(
                'ticket_qrcode_id',
                'scan_records_ticket_qrcode_unique'
            );

            // Index untuk pencarian/report
            $table->index(
                ['outlet_id', 'scanned_at'],
                'scan_records_outlet_scanned_at_index'
            );

            $table->index(
                ['user_id', 'scanned_at'],
                'scan_records_user_scanned_at_index'
            );

            $table->index(
                'qrcode',
                'scan_records_qrcode_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_records');
    }
};
