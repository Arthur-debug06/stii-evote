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
        Schema::create('signatory_action', function (Blueprint $table) {
            $table->id();
            $table->string('action_name'); // Based on your signatory_action model fillable
            $table->string('status'); // Based on your signatory_action model fillable
            $table->timestamps();
            $table->softDeletes(); // Based on your signatory_action model

            // Indexes
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatory_action');
    }
};
