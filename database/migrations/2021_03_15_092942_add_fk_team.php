<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('captain_id')->after('id');
            $table->unsignedBigInteger('game_id')->after('num_of_players');
            $table->foreign('captain_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['captain_id']);
            $table->dropColumn('captain_id');
            $table->dropForeign(['game_id']);
            $table->dropColumn('game_id');
        });
    }
}
