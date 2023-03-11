<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageFieldsToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('language_id');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->boolean('language_id')->default(1)->after('id');
            $table->unsignedInteger('existing_record_id')->nullable()->default(null)->after('id');
            $table->foreign('existing_record_id')->references('id')->on('contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('language_id');
            $table->dropForeign('contents_existing_record_id_foreign');
            $table->dropColumn('existing_record_id');
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->unsignedInteger('language_id')->default(1);
        });
    }
}
