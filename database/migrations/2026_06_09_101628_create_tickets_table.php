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
        Schema::create('tickets', function (Blueprint $table) {
           $table->id();

            // Ticket Number
            $table->string('ticket_number')->unique();

            // Ticket Info
            $table->string('title');
            $table->text('description')->nullable();

            // Category
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            // User Creator
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Assigned Person
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Priority
            $table->enum('priority', [
                'Low',
                'Medium',
                'High'
            ])->default('Medium');

            // Status
            $table->enum('status', [
                'Open',
                'In Progress',
                'Resolved',
                'Closed'
            ])->default('Open');

            // Notes / Comments
            $table->text('notes')->nullable();

            // Required Field
            $table->timestamp('created_date');

            $table->timestamps();

            // Index
            $table->index('ticket_number');
            $table->index('status');
            $table->index('priority');
            $table->index('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
