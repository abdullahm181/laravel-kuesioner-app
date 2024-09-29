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
        Schema::create('kuesioner_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuesioner_id');
            $table->unsignedBigInteger('kuesioner_section_id')->nullable();
            $table->unsignedBigInteger('question_type_id');
            $table->unsignedBigInteger('question_option_group_id')->nullable();
            $table->string('questionText');
            $table->string('questionSubText')->nullable();
            $table->integer('questionOrder');
            $table->boolean('aswareRequired'); 

            $table->unsignedBigInteger('depedent_question_id')->nullable();
            $table->unsignedBigInteger('depedent_question_option_id')->nullable();


            $table->boolean('isDeleted'); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps();

            $table->foreign('kuesioner_id')->references('id')->on('kuesioners');
            $table->foreign('kuesioner_section_id')->references('id')->on('kuesioner_sections');
            $table->foreign('question_type_id')->references('id')->on('kuesioner_question_types');
            $table->foreign('question_option_group_id')->references('id')->on('kuesioner_question_option_groups');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_questions');
    }
};
