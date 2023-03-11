<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pop', function (Blueprint $table) {
            $table->boolean('visible_in')->nullable()->default(null)->after('image');
            $table->boolean('url')->nullable()->default(null)->after('visible_in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pop', function (Blueprint $table) {
            $table->dropColumn('visible_in');
            $table->dropColumn('url');
        });
    }
}
