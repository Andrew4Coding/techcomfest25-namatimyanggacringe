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
        Schema::create('quiz_submission_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('answer')->nullable();
            $table->float('score')->default(0);

            $table->uuid('quiz_submission_id');
            $table->uuid('question_id');

            $table->foreign('quiz_submission_id')->references('id')
                ->on('quiz_submissions')->onDelete('cascade');
            $table->foreign('question_id')->references('id')
                ->on('questions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_submission_items');
    }
};
