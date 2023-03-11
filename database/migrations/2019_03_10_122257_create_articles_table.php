<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->default(1);
            $table->string('title');
            $table->string('short_description');
            $table->string('description');
            $table->string('url');
            $table->string('image');
            $table->unsignedInteger('display_order')->default(1);
            $table->boolean('is_active')->default(1);
            $table->unsignedTinyInteger('type')->default(1)->comment = "1=text,2=link";
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
