<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('visible_in')->default(1);
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');

            $table->foreign('existing_record_id')->references('id')->on('banners')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->dropColumn('visible_in');
        });
    }
}
