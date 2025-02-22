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

            $table->float('grade')->nullable();
            $table->string('comment')->nullable();
            $table->string('submission_urls');
            $table->integer('attempts')->default(1);

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
