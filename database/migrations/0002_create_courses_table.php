<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['SAFETY', 'CRANE_TYPE', 'OPERATION', 'TECHNICAL', 'RISK', 'CERTIFICATION', 'COMPANY'])->default('SAFETY');
            $table->enum('crane_type', ['MOBILE', 'TOWER', 'PORTAL', 'HIAB', 'AERIAL', 'TELESCOPIC'])->nullable();
            $table->enum('level', ['BEGINNER', 'INTERMEDIATE', 'ADVANCED', 'ALL_LEVELS'])->default('BEGINNER');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('thumbnail')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('is_mandatory')->default(false);
            $table->integer('passing_score')->default(70);
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
