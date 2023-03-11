<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('language_id')->default(1)->change();
            $table->string('title_prefix')->nullable()->after('description');
            $table->string('title_suffix')->nullable()->after('title');
            $table->string('link_text')->nullable();
            $table->string('link')->nullable();
            $table->string('link_target')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('title_prefix');
            $table->dropColumn('title_suffix');
            $table->dropColumn('link_text');
            $table->dropColumn('link');
            $table->dropColumn('link_target');
        });
    }
}
