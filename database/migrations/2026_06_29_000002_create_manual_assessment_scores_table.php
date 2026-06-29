<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained()->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('criteria')->cascadeOnDelete();
            $table->string('component_key');
            $table->string('component_name');
            $table->decimal('score', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(['alternative_id', 'criteria_id', 'component_key'], 'manual_assessment_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_assessment_scores');
    }
};
