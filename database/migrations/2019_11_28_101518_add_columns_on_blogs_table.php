<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('slug')->nullable();
            $table->string('excerpt')->nullable();

            $table->foreign('category_id')->references('id')->on('blog_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('blogs', function (Blueprint $table){
           $table->dropForeign(['category_id']);
           $table->dropColumn('category_id');
           $table->dropColumn('excerpt');
       });
    }
}
