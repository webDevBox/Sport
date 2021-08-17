<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('challanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challanger_id');
            $table->unsignedBigInteger('opponent_id');
            $table->text('message');
            $table->integer('status');
            $table->dateTime('proposed_time');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('actor_id');
            $table->foreign('challanger_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('opponent_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('challanges');
    }
}
