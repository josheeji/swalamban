<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forexes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('RTLIST_DATE')->nullable()->default(null);
            $table->string('FXD_CRNCY_CODE')->nullable()->default(null);
            $table->string('VAR_CRNCY_CODE')->nullable()->default(null);
            $table->string('FXD_CRNCY_UNITS')->nullable()->default(null);
            $table->string('BUY_RATE')->nullable()->default(null);
            $table->string('SELL_RATE')->nullable()->default(null);
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forexes');
    }
}
