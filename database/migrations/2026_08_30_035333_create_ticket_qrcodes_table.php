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
        Schema::create('ticket_qrcodes', function (Blueprint $table) {
            $table->id();
            $table->string('no_tiket', 100);

            $table->string('qrcode', 255);

            $table->string('ticket_type', 100);

            $table->text('remark')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('no_tiket');
            $table->index('qrcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_qrcodes');
    }
};
