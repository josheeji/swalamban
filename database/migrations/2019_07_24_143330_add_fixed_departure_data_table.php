<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFixedDepartureDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking', function ($table) {
            $table->unsignedInteger('fixed_departure_id')->nullable()->default(null);
            $table->foreign('fixed_departure_id')->references('id')->on('fixed_departures')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking', function ($table) {
            $table->dropForeign(['fixed_departure_id']);
            $table->dropColumn('fixed_departure_id');
        });
    }
}
