<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_enquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_type_id');
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_type_id')->references('id')->on('account_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_enquiries');
    }
}
