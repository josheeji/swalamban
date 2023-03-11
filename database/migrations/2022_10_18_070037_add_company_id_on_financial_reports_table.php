<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->string('company_id')->nullable()->comment('1 = Surya Life, 2= Jyoti Life')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
};