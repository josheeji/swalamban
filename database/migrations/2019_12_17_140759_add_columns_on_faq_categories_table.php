<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnFaqCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->after('id')->nullable();
            $table->boolean('language_id')->default(1)->change();
            $table->unsignedInteger('created_by')->after('is_active');
            $table->unsignedInteger('updated_by')->after('created_by')->nullable();

            $table->foreign('existing_record_id')->references('id')->on('faq_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropForeign(['existing_record_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn('existing_record_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->unsignedInteger('language_id')->default(1)->change();
        });
    }
}
