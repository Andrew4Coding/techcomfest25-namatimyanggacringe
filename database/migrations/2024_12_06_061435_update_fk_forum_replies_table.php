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
        //
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->uuid('reply_to_id')->nullable();
            $table->foreign('reply_to_id')->references('id')
                ->on('forum_replies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
