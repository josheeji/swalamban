<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveArticleCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_comments', function($table) {
            $table->integer('display_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(0);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('article_comments', function($table) {
         $table->dropColumn('is_active');
         $table->dropColumn('display_order');
      });
    }
}
