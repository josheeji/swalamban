<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageResizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_resize', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->string('alias')->nullable()->default(null);
            $table->integer('view_port_width')->unsigned()->default(0);
            $table->integer('view_port_height')->unsigned()->default(0);
            $table->integer('boundary_width')->unsigned()->default(0);
            $table->integer('boundary_height')->unsigned()->default(0);
            $table->integer('image_resize_width')->unsigned()->default(0);
            $table->integer('image_resize_height')->unsigned()->default(0);
            $table->boolean('is_active')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('image_resize');
    }
}
