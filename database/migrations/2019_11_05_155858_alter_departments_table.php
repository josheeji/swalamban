<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function ($table) {
            $table->dropColumn('excrept');
            $table->string('excerpt')->nullable()->default(null)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function ($table) {
            $table->dropColumn('excerpt');
            $table->string('excrept')->nullable()->default(null)->after('image');
        });
    }
}
