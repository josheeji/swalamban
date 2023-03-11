<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckBankGuaranteeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_bank_guarantee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('applicant')->nullable();
            $table->string('beneficiary')->nullable();
            $table->string('purpose')->nullable();
            $table->string('lcy_amount')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expiary_date')->nullable();
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
        Schema::dropIfExists('check_bank_guarantee');
    }
}
