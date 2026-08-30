<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {
            $table->dropForeign([
                'ticket_qrcode_id',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | HAPUS UNIQUE TICKET SAJA
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {
            $table->dropUnique(
                'scan_records_ticket_qrcode_unique'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | BUAT UNIQUE TICKET + OUTLET
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {

            $table->unique(
                [
                    'ticket_qrcode_id',
                    'outlet_id',
                ],
                'scan_records_ticket_outlet_unique'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | BUAT KEMBALI FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {

            $table->foreign('ticket_qrcode_id')
                ->references('id')
                ->on('ticket_qrcodes')
                ->cascadeOnDelete();
        });
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {
            $table->dropForeign([
                'ticket_qrcode_id',
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | HAPUS UNIQUE TICKET + OUTLET
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {
            $table->dropUnique(
                'scan_records_ticket_outlet_unique'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | KEMBALIKAN UNIQUE TICKET
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {

            $table->unique(
                'ticket_qrcode_id',
                'scan_records_ticket_qrcode_unique'
            );
        });


        /*
        |--------------------------------------------------------------------------
        | KEMBALIKAN FOREIGN KEY
        |--------------------------------------------------------------------------
        */

        Schema::table('scan_records', function (Blueprint $table) {

            $table->foreign('ticket_qrcode_id')
                ->references('id')
                ->on('ticket_qrcodes')
                ->cascadeOnDelete();
        });
    }
};
