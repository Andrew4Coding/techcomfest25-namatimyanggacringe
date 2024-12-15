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
        Schema::create('course_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->text('description');
            $table->boolean('isPublic')->default(true);

            $table->uuid('course_section_id');

            $table->foreign('course_section_id')->references('id')
                ->on('course_sections')->onDelete('cascade');

            $table->uuidMorphs('course_itemable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_items');
    }
};
