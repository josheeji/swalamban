<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrievancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->nullable()->unique();
            $table->unsignedInteger('branch_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('email');
            $table->string('full_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branch_directories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign("department_id")->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grievances');
    }
}
