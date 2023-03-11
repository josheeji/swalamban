<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->boolean('language_id')->default(1)->change();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('existing_record_id')->references('id')->on('faqs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('updated_by');
            $table->dropColumn('created_by');
            $table->dropColumn('existing_record_id');
            $table->unsignedInteger('language_id')->default(1)->change();
        });
    }
}
