<?php

// database/migrations/2025_09_22_000006_create_progress_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->float('completion_rate')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('last_accessed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('progress');
    }
};
