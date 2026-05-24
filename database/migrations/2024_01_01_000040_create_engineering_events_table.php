<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineering_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('source', ['github', 'manual']);
            $table->enum('event_type', ['push', 'pull_request', 'issue', 'error_log']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('actor')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('commit_hash')->nullable();
            $table->string('github_url')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineering_events');
    }
};
