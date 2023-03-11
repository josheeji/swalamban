<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('reference_id')->after('id')->nullable();
            $table->string('status')->after('cover_letter')->nullable();
            $table->unsignedInteger('updated_by')->nullable()->after('status');

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
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->dropColumn('status');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
    }
}
