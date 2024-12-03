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
        Schema::create('modality_visualizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_file_id');
            $table->foreign('image_file_id')->references('id')->on('files')->onDelete('cascade');
            $table->string('question');
            $table->json('choices');
            $table->string('correct_answer');
            $table->unsignedBigInteger('context_file_id');
            $table->foreign('context_file_id')->references('id')->on('files')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modality_visualizations');
    }
};
