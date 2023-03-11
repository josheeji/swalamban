<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->after('id')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->after('language_id');

            $table->foreign('existing_record_id')->references('id')->on('downloads')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('downloads')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign(['existing_record_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn('existing_record_id');
            $table->dropColumn('parent_id');
        });
    }
}
