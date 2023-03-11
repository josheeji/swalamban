<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->float('range_from', 14, 2)->nullable();
            $table->float('range_to', 14, 2)->nullable();
            $table->float('commission')->nullable();
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
        Schema::dropIfExists('broker_commissions');
    }
}
