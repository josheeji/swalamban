<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLayoutOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layout_options', function (Blueprint $table) {
            $table->string('block_title')->after('menu_id')->nullable();
            $table->string('subtitle')->after('block_title')->nullable();
            $table->string('image')->after('value')->nullable();
            $table->string('link')->after('image')->nullable();
            $table->string('link_text')->after('link')->nullable();
            $table->boolean('link_target')->after('link_text')->nullable()->default(0);
            $table->boolean('external_link')->after('link_target')->nullable()->default(0);
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
            $table->dropColumn('block_title');
            $table->dropColumn('subtitle');
            $table->dropColumn('image');
            $table->dropColumn('link');
            $table->dropColumn('link_text');
            $table->dropColumn('link_target');
            $table->dropColumn('external_link');
        });
    }
}
