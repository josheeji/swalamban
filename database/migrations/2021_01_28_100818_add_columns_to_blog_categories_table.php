<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->nullable()->after('id');
            $table->renameColumn('name', 'title');

            $table->foreign('existing_record_id')->references('id')->on('blog_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->dropForeign(['existing_record_id']);
            $table->dropColumn('existing_record_id');
        });
    }
}
