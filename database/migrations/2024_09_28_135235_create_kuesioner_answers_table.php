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
        Schema::create('kuesioner_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id');
            $table->unsignedBigInteger('question_id');
            $table->string('answer')->nullable();

            $table->boolean('isDeleted'); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps();

            $table->foreign('response_id')->references('id')->on('kuesioner_responses');
            $table->foreign('question_id')->references('id')->on('kuesioner_questions');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_answers');
    }
};
