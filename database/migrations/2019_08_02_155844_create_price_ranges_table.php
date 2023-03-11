<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id')->nullable()->default(null);
            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->string('traveller_range')->nullable()->default(null);
            $table->string('amount')->nullable()->default(null);
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('price_ranges');
    }
}
