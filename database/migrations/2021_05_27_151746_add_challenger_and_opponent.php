<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChallengerAndOpponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('matches', function (Blueprint $table) {

            $table->unsignedBigInteger('challanger_id')->after('challange_id');
            $table->foreign('challanger_id')->references('id')->on('teams')->onDelete('cascade');

            $table->unsignedBigInteger('opponent_id')->after('challanger_id');
            $table->foreign('opponent_id')->references('id')->on('teams')->onDelete('cascade');

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
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['challanger_id']);
            $table->dropColumn('challanger_id');
            $table->dropForeign(['opponent_id']);
            $table->dropColumn('opponent_id');
        });
    }
}
