<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['JUNIOR', 'OPERATOR', 'SENIOR', 'SUPERVISOR', 'TRAINER'])->default('JUNIOR');
            $table->string('cert_number')->unique();
            $table->enum('status', ['ACTIVE', 'EXPIRED', 'REVOKED'])->default('ACTIVE');
            $table->string('recipient_name')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('department')->nullable();
            $table->string('site')->nullable();
            $table->string('instructor_name')->nullable();
            $table->integer('training_hours')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::create('certificate_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->unique()->constrained()->onDelete('cascade');
            $table->enum('cert_level', ['JUNIOR', 'OPERATOR', 'SENIOR', 'SUPERVISOR', 'TRAINER'])->default('OPERATOR');
            $table->integer('completion_days')->default(30);
            $table->integer('validity_days')->default(365);
            $table->boolean('requires_quiz')->default(true);
            $table->integer('min_watch_pct')->default(80);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('cert_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_id')->constrained('certificate_configs')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->unique(['config_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cert_prerequisites');
        Schema::dropIfExists('certificate_configs');
        Schema::dropIfExists('certificates');
    }
};
