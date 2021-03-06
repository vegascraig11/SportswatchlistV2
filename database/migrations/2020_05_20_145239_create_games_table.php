<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('GlobalGameID')->unique();
            $table->integer('GameID')->nullable();
            $table->integer('GlobalAwayTeamID');
            $table->integer('GlobalHomeTeamID');
            $table->string('Date');
            $table->string('Status');
            $table->string('StadiumID');

            $table->string('GameType');
            $table->json('All');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
