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
        Schema::create('set_signatory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id'); // Based on your set_signatory model fillable
            $table->string('position')->nullable(); // Based on your set_signatory model fillable
            $table->string('academic_suffix')->nullable(); // Based on your set_signatory model fillable
            $table->unsignedBigInteger('signatory_action_id')->nullable(); // Based on your set_signatory model fillable
            $table->string('status'); // Based on your set_signatory model fillable
            $table->timestamps();
            $table->softDeletes(); // Based on your set_signatory model

            // Foreign keys
            $table->foreign('signatory_action_id')->references('id')->on('signatory_action')->onDelete('set null');

            // Indexes
            $table->index(['users_id']);
            $table->index(['signatory_action_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_signatory');
    }
};
