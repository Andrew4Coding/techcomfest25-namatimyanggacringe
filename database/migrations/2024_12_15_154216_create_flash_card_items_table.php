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
        Schema::create('flash_card_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('question');
            $table->string('answer');

            $table->uuid('flash_card_id');
            $table->foreign('flash_card_id')->references('id')
                ->on('flash_cards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_card_items');
    }
};
