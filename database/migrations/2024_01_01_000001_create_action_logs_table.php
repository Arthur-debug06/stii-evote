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
        Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->nullable(); // Model class name
            $table->unsignedBigInteger('document_id')->nullable(); // Model ID
            $table->unsignedBigInteger('user_id')->nullable(); // Who performed the action
            $table->unsignedBigInteger('created_by')->nullable(); // Who created the log
            $table->string('action'); // Action performed (created, updated, deleted, etc.)
            $table->text('details')->nullable(); // Action details
            $table->text('remarks')->nullable(); // Additional remarks
            $table->string('trackable_type')->nullable(); // For polymorphic relation
            $table->unsignedBigInteger('trackable_id')->nullable(); // For polymorphic relation
            $table->string('ip_address')->nullable(); // IP address
            $table->string('user_agent')->nullable(); // Browser info
            $table->string('location')->nullable(); // Location info
            $table->string('batch_uuid')->nullable(); // For batch operations
            $table->timestamps();

            // Indexes
            $table->index(['document_type', 'document_id']);
            $table->index(['trackable_type', 'trackable_id']);
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['batch_uuid']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
