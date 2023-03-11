<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('career_id');
            $table->string('full_name');
            $table->string('email');
            $table->string('contact_no');
            $table->string('address');
            $table->string('message');
            $table->string('resume');
            $table->string('cover_letter');
            $table->timestamps();

            $table->foreign('career_id')->references('id')->on('careers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
