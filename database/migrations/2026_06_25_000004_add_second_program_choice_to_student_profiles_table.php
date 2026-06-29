<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('study_program_2')->nullable()->after('study_program_accreditation');
            $table->string('study_program_accreditation_2')->nullable()->after('study_program_2');
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['study_program_2', 'study_program_accreditation_2']);
        });
    }
};
