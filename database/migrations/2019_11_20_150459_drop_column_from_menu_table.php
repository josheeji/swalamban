<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
            $table->boolean('location_main')->default(0)->change();
            $table->unsignedInteger('reference_id')->nullable()->after('language_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->boolean('location_main')->default(0)->change();
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->foreign('deleted_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->dropColumn('reference_id');
        });
    }
}
