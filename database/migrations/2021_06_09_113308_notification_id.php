<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationId extends Migration
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
            $table->unsignedBigInteger('notification_id')->nullable()->after('match_id');
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
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
            $table->dropForeign(['notification_id']);
            $table->dropColumn('notification_id');
        });
    }
}
