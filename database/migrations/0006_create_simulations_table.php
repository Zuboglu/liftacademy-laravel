<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->text('scenario');
            $table->integer('difficulty')->default(1); // 1-5
            $table->timestamps();
        });

        Schema::create('simulation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('simulation_id')->constrained()->onDelete('cascade');
            $table->integer('score');
            $table->boolean('passed');
            $table->json('decisions');
            $table->timestamp('completed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_attempts');
        Schema::dropIfExists('simulations');
    }
};
