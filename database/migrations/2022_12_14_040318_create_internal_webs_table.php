<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_webs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->unsignedInteger('language_id')->default(1);
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('year')->nullable();
            $table->unsignedInteger('month')->nullable();
            $table->text('title');
            $table->string('slug');
            $table->text('file');
            $table->text('description')->nullable();
            $table->string('type')->default('D');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->date('published_date')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('display_order')->default(1);
            $table->foreign('category_id')->references('id')->on('internal_web_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('existing_record_id')->references('id')->on('internal_webs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('internal_webs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins');
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->foreign('deleted_by')->references('id')->on('admins');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_webs');
    }
};
