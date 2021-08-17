<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGameIdAndTeamColumnNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id')->after('id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->tinyInteger('team')->default(0)->comment('0=allTeams , 1=specificTeams')->after('game_id');
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
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['game_id']);
            $table->dropColumn('game_id');
            $table->dropColumn('team');
        });
    }
}
