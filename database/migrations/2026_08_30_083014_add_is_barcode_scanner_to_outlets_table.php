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
        Schema::table('outlets', function (Blueprint $table) {
            $table->boolean('is_camera_enabled')
                ->default(true)
                ->after('is_active');

            $table->boolean('is_scanner_enabled')
                ->default(false)
                ->after('is_camera_enabled');

            $table->text('remark')
                ->nullable()
                ->after('is_scanner_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outlets', function (Blueprint $table) {
            $table->dropColumn([
                'is_camera_enabled',
                'is_scanner_enabled',
                'remark',
            ]);
        });
    }
};
