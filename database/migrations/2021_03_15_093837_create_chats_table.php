<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sender_team');
            $table->foreign('sender_team')->references('id')->on('teams')->onDelete('cascade');

            $table->unsignedBigInteger('receiver_team');
            $table->foreign('receiver_team')->references('id')->on('teams')->onDelete('cascade');
            
            $table->unsignedBigInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            
            $table->string('chat_id');

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
        Schema::dropIfExists('chats');
    }
}
