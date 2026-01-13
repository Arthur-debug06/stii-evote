<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add a unique index on course_name to enforce uniqueness at the database level.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('course')) {
            Schema::table('course', function (Blueprint $table) {
                // Only add the index if it doesn't already exist
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = array_map(fn($i) => $i->getName(), $sm->listTableIndexes('course'));
                if (!in_array('course_course_name_unique', $indexes, true)) {
                    $table->unique('course_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('course')) {
            Schema::table('course', function (Blueprint $table) {
                $table->dropUnique(['course_name']);
            });
        }
    }
};
