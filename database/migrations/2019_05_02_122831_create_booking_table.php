<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id')->nullable()->default(null);
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->integer('no_person')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('country_id')->nullable()->default(null);
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->text('message')->nullable();
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('booking');
    }
}
