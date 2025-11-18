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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Notification class name
            $table->unsignedBigInteger('user_id')->nullable(); // User who receives notification
            $table->unsignedBigInteger('signatory_id')->nullable(); // Related signatory
            $table->string('release_type')->nullable(); // quick_release, trail_release, owner_notification
            $table->string('status')->default('pending'); // pending, approved, rejected, etc.
            $table->text('message'); // Notification message
            $table->string('title')->nullable(); // Notification title
            $table->string('icon')->nullable(); // Icon name
            $table->string('icon_color')->nullable(); // Icon color
            $table->string('url')->nullable(); // Related URL
            $table->string('pdf_url')->nullable(); // PDF preview URL
            $table->string('documentable_type')->nullable(); // For polymorphic relation
            $table->unsignedBigInteger('documentable_id')->nullable(); // For polymorphic relation
            $table->string('notifiable_type')->nullable(); // For polymorphic relation
            $table->string('notifiable_id')->nullable(); // For polymorphic relation
            $table->json('data')->nullable(); // Additional data
            $table->json('view_data')->nullable(); // View-specific data
            $table->timestamp('read_at')->nullable(); // When notification was read
            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->index(['signatory_id']);
            $table->index(['status']);
            $table->index(['documentable_type', 'documentable_id']);
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['read_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
