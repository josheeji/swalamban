<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToLayoutOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layout_options', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->after('id')->nullable();
            $table->unsignedInteger('language_id')->after('existing_record_id')->default(1);

            $table->foreign('existing_record_id')->references('id')->on('layout_options')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->dropColumn('language_id');
        });
    }
}
