<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->boolean('language_id')->default(1)->nullable();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('value')->nullable();
            $table->string('display_order')->nullable()->default(0);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('existing_record_id')->references('id')->on('body_menus')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('body_menus');
    }
}
