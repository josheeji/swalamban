<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToGrievancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->boolean('existing_customer')->default(1)->after('message');
            $table->boolean('grant_authorization')->default(0)->after('existing_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->dropColumn('existing_customer');
            $table->dropColumn('grant_authorization');
        });
    }
}
