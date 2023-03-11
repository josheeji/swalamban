<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticeCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id')->nullable()->default(null);
            $table->foreign('article_id')
                  ->references('id')
                  ->on('articles')
                  ->onUpdate('RESTRICT')
                  ->onDelete('CASCADE');
            $table->string('full_name');      
            $table->string('email');   
            $table->text('comment');   
            $table->softDeletes();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_comments');
    }
}
