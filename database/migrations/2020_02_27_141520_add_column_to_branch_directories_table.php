<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBranchDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_directories', function (Blueprint $table) {
            $table->string('type')->nullable()->after('inside_valley');
            $table->unsignedInteger('district_id')->nullable()->change();
            $table->unsignedInteger('province_id')->nullable()->change();
            $table->string('fullname')->nullable()->change();
            $table->string('prefix')->nullable()->change();
            $table->string('designation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_directories', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedInteger('district_id')->nullable()->change();
            $table->unsignedInteger('province_id')->nullable()->change();
            $table->string('fullname')->nullable()->change();
            $table->string('prefix')->nullable()->change();
            $table->string('designation')->nullable()->change();
        });
    }
}
