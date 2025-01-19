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
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('content');
            $table->integer('upvote')->default(0);
            $table->integer('downvote')->default(0);
            $table->boolean('is_ai')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_public')->default(true);

            $table->uuid('forum_discussion_id');
            $table->uuid('sender_id')->nullable();

            $table->foreign('forum_discussion_id')->references('id')
                ->on('forum_discussions')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')
                ->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
    }
};
