<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_accesses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_type_id');
            $table->unsignedInteger('module_id');
            $table->boolean('view')->default(0);
            $table->boolean('add')->default(0);
            $table->boolean('edit')->default(0);
            $table->boolean('delete')->default(0);
            $table->boolean('changeStatus')->default(0);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('admin_type_id')->references('id')->on('admin_types')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_accesses');
    }
}
