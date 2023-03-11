<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('department_id')->after('id');
            $table->integer('gender')->after('full_name');
            $table->string('dob')->after('gender');
            $table->string('address')->after('dob');
            $table->boolean('is_confirmed')->after('message')->default(0);
            $table->boolean('is_active')->default(1)->after('is_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('address');
            $table->dropColumn('is_confirmed');
            $table->dropColumn('is_active');
        });
    }
}
