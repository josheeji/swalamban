<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnOnNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->string('excerpt')->nullable()->after('image');
            $table->boolean('layout')->default(1)->after('description');
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->foreign('existing_record_id')->references('id')->on('news')->onUpdate('cascade')->onDelete('cascade');
            $table->dropUnique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('excerpt');
            $table->string('short_description')->nullable();
            $table->dropColumn('layout');
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->unique('slug');
        });
    }
}
