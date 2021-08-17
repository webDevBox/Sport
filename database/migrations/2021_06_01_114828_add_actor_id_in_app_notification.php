<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActorIdInAppNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('challange_id')->nullable()->after('receiver_id');
            $table->foreign('challange_id')->references('id')->on('challanges')->onDelete('cascade');
            
            $table->unsignedBigInteger('match_id')->nullable()->after('challange_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
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
        Schema::table('app_notifications', function (Blueprint $table) {
            $table->dropForeign(['challange_id']);
            $table->dropColumn('challange_id');
            
            $table->dropForeign(['match_id']);
            $table->dropColumn('match_id');
        });
    }
}
