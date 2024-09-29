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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('ModuleName');
            $table->string('ModuleGroup')->nullable();
            $table->string('ModuleSubGroup')->nullable();
            $table->string('ModuleAction')->nullable();
            $table->string('ModuleController');
            $table->integer('ModuleOrder');
            $table->string('ModuleIcon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module');
    }
};
