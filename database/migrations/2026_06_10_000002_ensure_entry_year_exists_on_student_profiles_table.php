<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('student_profiles', 'entry_year')) {
            Schema::table('student_profiles', function (Blueprint $table) {
                $table->unsignedSmallInteger('entry_year')->nullable()->after('school_origin')->index();
            });
        }
    }

    public function down(): void
    {
        // This migration repairs schema drift and must not remove a valid column.
    }
};
