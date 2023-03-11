<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->default(1);
            $table->unsignedInteger('parent_id')->nullable()->default(null);
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('meta_keys')->nullable()->default(null);
            $table->text('meta_desc')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->text('excerpt')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('display_order')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->boolean('is_active')->default(1);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('contents')->onDelete('cascade');
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
        Schema::dropIfExists('contents');
    }
}
