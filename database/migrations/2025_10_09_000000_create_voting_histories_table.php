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
        Schema::create('voting_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('voting_exclusive_id')->index();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable()->index();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->integer('total_voters')->default(0);
            $table->integer('total_votes')->default(0);
            $table->json('winner_summary')->nullable();
            $table->json('result_summary')->nullable();
            $table->timestamps();

            // foreign keys are intentionally not added here to avoid cross-db issues in different environments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_histories');
    }
};
