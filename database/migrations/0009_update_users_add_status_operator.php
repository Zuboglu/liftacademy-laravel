<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // role enum'una OPERATOR ekle
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('STUDENT','OPERATOR','INSTRUCTOR','SUPERVISOR','ADMIN') DEFAULT 'STUDENT'");

        // status kolonu ekle
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED'])->default('ACTIVE')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('STUDENT','INSTRUCTOR','SUPERVISOR','ADMIN') DEFAULT 'STUDENT'");
    }
};
