<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGalleryVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->boolean('language_id')->default(1)->after('existing_record_id')->change();
            $table->integer('display_order')->after('link')->default(0);

            $table->foreign('existing_record_id')->references('id')->on('gallery_videos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
            $table->boolean('language_id')->change();
            $table->dropColumn('display_order');
        });
    }
}
