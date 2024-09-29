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
        Schema::create('kuesioner_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuesioner_id');
            $table->unsignedBigInteger('respondent_id')->nullable();
            $table->string('respondentEmail');
            $table->dateTime('beginDate');
            $table->dateTime('endDate');
           
            $table->boolean('isDeleted'); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps();

            $table->foreign('kuesioner_id')->references('id')->on('kuesioners');
            $table->foreign('respondent_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_responses');
    }
};
