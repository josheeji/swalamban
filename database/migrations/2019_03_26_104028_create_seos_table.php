<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seos', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('seoable');
            $table->string('page');
            $table->string('meta_title')->nullable()->default(null);
            $table->string('meta_keywords')->nullable()->default(null);
            $table->string('meta_description')->nullable()->default(null);
            $table->string('slug')->unique();
            $table->boolean('deletable')->default(1);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seos');
    }
}
