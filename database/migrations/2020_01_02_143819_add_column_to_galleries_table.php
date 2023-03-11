<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->after('id')->nullable();
            $table->boolean('language_id')->default(1)->change();
            $table->dropUnique(['slug']);
            $table->foreign('existing_record_id')->references('id')->on('galleries')->onCascade('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->boolean('language_id')->default(1)->change();
            $table->unique('slug');
        });
    }
}
