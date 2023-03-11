<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtmLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atm_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->boolean('language_id')->default(1);
            $table->string('title');
            $table->string('slug');
            $table->boolean('inside_valley')->default(1);
            $table->string('address')->nullable();
            $table->string('ward_no')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('province_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('existing_record_id')->references('id')->on('atm_locations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('atm_locations');
    }
}
