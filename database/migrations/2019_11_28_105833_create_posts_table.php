<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('language_id')->default(1);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->boolean('type');
            $table->string('title');
            $table->string('slug');
            $table->string('banner_image')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('icon')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('post_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
