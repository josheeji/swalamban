<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pop', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->boolean('show_title')->default(1)->after('slug');
            $table->text('description')->nullable()->after('image');
            $table->boolean('show_in_notification')->default(1)->after('description');
            $table->boolean('target')->default(0)->after('url');
            $table->string('btn_label')->nullable()->after('target');
            $table->boolean('show_button')->default(1)->after('btn_label');
            $table->string('image')->nullable()->change();
            $table->boolean('show_image')->default(1)->after('image');
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
            $table->dropColumn('slug');
            $table->dropColumn('show_title');
            $table->dropColumn('description');
            $table->dropColumn('target');
            $table->dropColumn('btn_label');
            $table->dropColumn('show_button');
            $table->dropColumn('show_in_notification');
            $table->dropColumn('show_image');
        });
    }
}
