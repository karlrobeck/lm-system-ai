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
            $table->integer('correct');
            $table->integer('total');
            $table->foreignId('file_id')->references('id')->on('files');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('rank')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->enum('test_type',['pre','post']);
            $table->enum('modality',['auditory','reading','visualization','writing','kinesthetic']);
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
