<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->renameColumn('banner_image', 'banner');
            $table->renameColumn('feature_image', 'image');
            $table->string('meta_keyword')->nullable()->after('slug');
            $table->string('meta_description')->nullable()->after('meta_keyword');
            $table->string('banner_alt')->nullable()->after('meta_description');
            $table->string('image_alt')->nullable()->after('banner_alt');
            $table->string('url')->nullable()->after('description');
            $table->string('link_target')->nullable()->after('url');
            $table->boolean('visible_in')->default(1)->after('description');

            $table->foreign('existing_record_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('banner', 'banner_image');
            $table->renameColumn('image', 'feature_image');
            $table->dropColumn('url');
            $table->dropColumn('link_target');
            $table->dropColumn('banner_alt');
            $table->dropColumn('image_alt');
            $table->dropColumn('meta_keyword');
            $table->dropColumn('meta_description');
            $table->dropColumn('visible_in');

            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
        });
    }
}
