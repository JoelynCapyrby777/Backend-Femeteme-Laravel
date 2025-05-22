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
        Schema::create('ranking_point_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('min_difference');
            $table->unsignedInteger('max_difference')->nullable(); 
            $table->unsignedInteger('positive_points');
            $table->unsignedInteger('negative_points');
            $table->enum('type', ['femeteme', 'no_oficial']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_point_rules');
    }
};
