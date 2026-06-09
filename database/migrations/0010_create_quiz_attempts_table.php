<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
                $table->integer('score'); // 0-100
                $table->boolean('passed');
                $table->json('answers'); // {questionId: selectedIndex}
                $table->integer('correct_count')->default(0);
                $table->integer('total_questions')->default(0);
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('finished_at')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_attempts', 'correct_count')) {
                    $table->integer('correct_count')->default(0)->after('answers');
                }
                if (!Schema::hasColumn('quiz_attempts', 'total_questions')) {
                    $table->integer('total_questions')->default(0)->after('correct_count');
                }
                if (!Schema::hasColumn('quiz_attempts', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
