<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->default(1);
            $table->string('title');
            $table->string('slug');
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->boolean('type')->default(0);
            $table->boolean('is_external')->default(0);
            $table->string('link_url')->nullable();
            $table->boolean('link_active')->default(1);
            $table->integer('block')->default(0);
            $table->boolean('link_target')->default(0);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->index(['parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
