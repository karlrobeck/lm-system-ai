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
        Schema::create('modality_reading_writings', function (Blueprint $table) {
            $table->id();
            $table->enum('test_type', ['pre', 'post']);
            $table->enum('mode', ['reading', 'writing']);
            $table->string('question');
            $table->string('context_answer');
            $table->json('choices')->nullable();
            $table->unsignedBigInteger('context_file_id');
            $table->foreign('context_file_id')->references('id')->on('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modality_reading_writings');
    }
};
