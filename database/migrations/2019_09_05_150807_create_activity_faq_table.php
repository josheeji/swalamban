<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('faq_activity_id');
            $table->text('question');
            $table->text('answer');
            $table->boolean('is_active')->nullable()->default(1);
            $table->unsignedInteger('display_order')->default(1);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('faq_activity_id')->references('id')->on('activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_faqs');
    }
}
