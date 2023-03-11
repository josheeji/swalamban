<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->boolean('language_id')->default(1)->after('existing_record_id');
            $table->unsignedInteger('branch_id')->nullable()->after('language_id');
            $table->string('email')->nullable()->after('description');
            $table->string('phone')->nullable()->after('email');
            $table->string('fax')->nullable()->after('phone');

            $table->foreign('existing_record_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branch_directories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('fax');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('branch_id');
            $table->dropColumn('language_id');
            $table->dropColumn('existing_record_id');
        });
    }
}
