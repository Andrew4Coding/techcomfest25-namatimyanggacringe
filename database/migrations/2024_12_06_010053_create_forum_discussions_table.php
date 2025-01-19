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
        Schema::create('forum_discussions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('content');
            $table->boolean('is_public')->default(true);

            $table->uuid('creator_id')->nullable();
            $table->uuid('forum_id');

            $table->foreign('creator_id')->references('id')
                ->on('users')->onDelete('set null');
            $table->foreign('forum_id')->references('id')
                ->on('forums')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};
