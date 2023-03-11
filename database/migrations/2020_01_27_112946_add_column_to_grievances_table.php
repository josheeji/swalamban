<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToGrievancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grievances', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->after('message');
            $table->unsignedInteger('updated_by')->nullable()->after('is_active');

            $table->foreign('updated_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropColumn('is_active');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
    }
}
