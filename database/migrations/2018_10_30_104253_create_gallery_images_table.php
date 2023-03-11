<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->default(1);
            $table->unsignedInteger('gallery_id');
            $table->string('image');
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->boolean('is_active')->default(1);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
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
        Schema::dropIfExists('gallery_images');
    }
}
