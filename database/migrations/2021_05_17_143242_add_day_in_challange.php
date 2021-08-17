<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDayInChallange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('challanges', function (Blueprint $table) {
            $table->unsignedBigInteger('day_id')->after('proposed_time');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
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
        Schema::table('challanges', function (Blueprint $table) {
            $table->dropForeign(['day_id']);
            $table->dropColumn('day_id');
        });
    }
}
