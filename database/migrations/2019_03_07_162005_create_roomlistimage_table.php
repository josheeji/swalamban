<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomlistimageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roomlistimage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->longText('description');
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('roomlist_id');
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('roomlist_id')->references('id')->on('roomlist')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roomlistimage');
    }
}
