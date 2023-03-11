<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnForexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forexes', function (Blueprint $table) {
            $table->string('BUY_RATE_ABOVE')->nullable()->after('BUY_RATE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forexes', function (Blueprint $table) {
            $table->dropColumn('BUY_RATE_ABOVE');
        });
    }
}
