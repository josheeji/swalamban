<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToRemitAllianceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remit_alliance_requests', function (Blueprint $table) {
            $table->boolean('type')->nullable()->default(1)->after('message')->comment = '1 Alliance Request; 2 Contact';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remit_alliance_requests', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
