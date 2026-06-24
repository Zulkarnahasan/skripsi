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
        Schema::table('test_settings', function (Blueprint $table) {
            $table->boolean('is_open')->default(false)->after('duration_minutes');
            $table->text('instruction')->nullable()->after('is_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_settings', function (Blueprint $table) {
            $table->dropColumn(['is_open', 'instruction']);
        });
    }
};
