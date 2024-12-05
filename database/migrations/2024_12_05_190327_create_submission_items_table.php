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
        Schema::create('submission_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->float('grade');
            $table->string('comment');
            $table->string('submission_url');

            $table->uuid('student_id');
            $table->uuid('submission_id');

            $table->foreign('student_id')->references('id')
                ->on('students')->onDelete('cascade');
            $table->foreign('submission_id')->references('id')
                ->on('submissions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_items');
    }
};
