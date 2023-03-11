<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layout_options', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->foreign('menu_id')->references('id')->on('menu')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layout_options', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->foreign('menu_id')->references('id')->on('menu')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
