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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();

              // Unique code untuk identitas outlet
            $table->string('outlet_code', 50)->unique();

            // Nama outlet / wahana
            $table->string('outlet_name', 150);

            // Tipe outlet
            $table->string('outlet_type', 50);

            // Status outlet
            $table->boolean('is_active')->default(true);



            $table->timestamps();

                 $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
