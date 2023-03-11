<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnOnMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('module_id')->nullable()->change();
            $table->unsignedInteger('reference_id')->nullable()->after('menu_id');
            $table->integer('display_order')->default(0)->after('link_target');
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('module_id')->nullable()->change();
            $table->dropColumn('reference_id');
            $table->dropColumn('display_order');
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->foreign('deleted_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
