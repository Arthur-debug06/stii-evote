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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('school_year_and_semester_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('student_id_image')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Based on your students model

            // Indexes
            $table->index(['student_id']);
            $table->index(['email']);
            $table->index(['course_id']);
            $table->index(['department_id']);
            $table->index(['school_year_and_semester_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
