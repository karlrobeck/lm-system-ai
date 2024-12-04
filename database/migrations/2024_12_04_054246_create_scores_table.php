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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->enum('test_type', ['pre', 'post']);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('context_file_id');
            $table->foreign('context_file_id')->references('id')->on('files');
            $table->enum('modality', ['visualization', 'reading-writing', 'auditory']);
            $table->integer('score');
            $table->integer('total');
            $table->integer('correct');
            $table->integer('incorrect');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
