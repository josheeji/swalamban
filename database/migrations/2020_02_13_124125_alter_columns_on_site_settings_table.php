<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsOnSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('slug');
            $table->dropColumn('type');
            $table->dropColumn('description');
            $table->string('key_group')->nullable()->after('key');
            $table->boolean('language_id')->default(1)->after('key_group');
            $table->unsignedInteger('existing_record_id')->nullable()->after('language_id');

            $table->foreign('existing_record_id')->references('id')->on('site_settings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('key_group');
            $table->string('name')->nullable()->after('key');
            $table->string('slug')->nullable()->after('name');
            $table->string('description')->nullable()->after('slug');
            $table->boolean('type')->default(0)->after('description')->comment = "1=Link, 2=Text, 3=Image";
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->dropColumn('language_id');
        });
    }
}
