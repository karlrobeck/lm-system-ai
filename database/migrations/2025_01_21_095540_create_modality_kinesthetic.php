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
        Schema::create('modality_kinesthetics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('files');
            $table->text('question');
            $table->text('context_answer');
            $table->integer('question_index')->nullable();
            $table->enum('test_type',['pre','post']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modality_kinesthetics');
    }
};
