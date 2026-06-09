<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('order')->default(0);
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['VIDEO', 'DOCUMENT', 'QUIZ', 'SIMULATION'])->default('VIDEO');
            $table->string('video_url')->nullable();
            $table->string('document_url')->nullable();
            $table->integer('duration')->nullable(); // saniye
            $table->integer('order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('sections');
    }
};
