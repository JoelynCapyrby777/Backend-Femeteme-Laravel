<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTennisMatchesTable extends Migration
{
    public function up()
    {
        Schema::create('tennis_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_one_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('player_two_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('winner_id')->constrained('players')->onDelete('cascade');
            $table->enum('event_type', ['femeteme', 'no_oficial']);
            $table->integer('point_difference');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tennis_matches');
    }
}
