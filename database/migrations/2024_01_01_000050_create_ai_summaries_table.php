<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineering_event_id')->constrained()->cascadeOnDelete();
            $table->text('technical_summary')->nullable();
            $table->text('business_summary')->nullable();
            $table->text('client_friendly_summary')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->text('business_impact')->nullable();
            $table->text('recommended_action')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_summaries');
    }
};
