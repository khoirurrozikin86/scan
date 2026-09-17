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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Actor
            |--------------------------------------------------------------------------
            |
            | User yang melakukan aktivitas.
            | Bisa NULL karena beberapa aktivitas dilakukan oleh system,
            | contohnya callback ESPay.
            |
            */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Audit Information
            |--------------------------------------------------------------------------
            */

            $table->string('action', 50);

            $table->string('module', 50);

            $table->string('description', 500);

            /*
            |--------------------------------------------------------------------------
            | Auditable Model
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | Ticket #123
            | Payment #10
            | Order #20
            | Product #5
            |
            */

            $table->nullableMorphs('auditable');

            /*
            |--------------------------------------------------------------------------
            | Before / After
            |--------------------------------------------------------------------------
            |
            | old_values = kondisi sebelum perubahan
            | new_values = kondisi setelah perubahan
            |
            */

            $table->json('old_values')
                ->nullable();

            $table->json('new_values')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Request Information
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('ip_address')
                ->nullable();

            $table->string('method', 10)
                ->nullable();

            $table->text('url')
                ->nullable();

            $table->text('user_agent')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['module', 'action'],
                'audit_logs_module_action_index'
            );

            $table->index(
                ['user_id', 'created_at'],
                'audit_logs_user_created_index'
            );

            $table->index(
                ['module', 'created_at'],
                'audit_logs_module_created_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
