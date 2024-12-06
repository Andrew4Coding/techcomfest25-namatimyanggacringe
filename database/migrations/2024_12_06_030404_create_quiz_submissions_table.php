<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quiz_id');
            $table->uuid('student_id');

            $table->foreign('quiz_id')->references('id')
                ->on('quizzes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')
                ->on('students')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_submissions');
    }
};
