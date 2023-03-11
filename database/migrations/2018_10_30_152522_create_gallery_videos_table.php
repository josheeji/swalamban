<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->default(1);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable()->default(null);
            $table->string('link')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->boolean('is_active')->default(1);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('updated_by')->references('id')->on('admins');
            $table->foreign('created_by')->references('id')->on('admins');
            $table->foreign('deleted_by')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_videos');
    }
}
