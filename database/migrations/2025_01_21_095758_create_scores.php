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
        Schema::create('scores_context', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->integer('question_index');
            $table->string('gpt_response');
            $table->boolean('is_correct');
            $table->timestamps();
        });
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->integer('correct');
            $table->integer('total');
            $table->foreignId('file_id')->references('id')->on('files');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->enum('test_type',['pre','post']);
            $table->enum('modality',['auditory','reading','visualization','writing']);
            $table->foreignId('scores_context_id')->references('id')->on('scores_context');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores_context');
        Schema::dropIfExists('scores');
    }
};
