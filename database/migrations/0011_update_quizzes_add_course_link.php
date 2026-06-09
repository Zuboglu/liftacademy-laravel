<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // quiz_questions tablosuna timestamps ekle (yoksa)
        if (Schema::hasTable('quiz_questions')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('quiz_questions', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        // certificates tablosuna issued_at ekle (yoksa)
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (!Schema::hasColumn('certificates', 'issued_at')) {
                    $table->timestamp('issued_at')->nullable()->after('completed_at');
                }
            });
        }
    }

    public function down(): void {}
};
