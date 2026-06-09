<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('quizzes', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('attempts');
            }
        });

        // quiz_attempts tablosunda correct_count ve total_questions eksik olabilir
        if (Schema::hasTable('quiz_attempts')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_attempts', 'correct_count')) {
                    $table->integer('correct_count')->default(0)->after('passed');
                }
                if (!Schema::hasColumn('quiz_attempts', 'total_questions')) {
                    $table->integer('total_questions')->default(0)->after('correct_count');
                }
                if (!Schema::hasColumn('quiz_attempts', 'answers')) {
                    $table->json('answers')->nullable()->after('total_questions');
                }
            });
        }
    }

    public function down(): void {}
};
