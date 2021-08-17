<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('challange_id');
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('won_by')->nullable();
            $table->foreign('challange_id')->references('id')->on('challanges')->onDelete('cascade');
            $table->foreign('won_by')->references('id')->on('teams')->onDelete('cascade');
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
        Schema::dropIfExists('matches');
    }
}
