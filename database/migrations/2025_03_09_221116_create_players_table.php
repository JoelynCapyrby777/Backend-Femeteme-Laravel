<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('curp')->unique();
            $table->unsignedInteger('age');
            $table->enum('category', ['femenil', 'varonil']);
            $table->unsignedInteger('ranking_position')->nullable();
            $table->foreignId('association_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
