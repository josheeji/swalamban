<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->boolean('language_id')->default(1);
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('banner')->nullable();
            $table->string('image')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->text('faq')->nullable();
            $table->string('interest_rate')->nullable();
            $table->string('link')->nullable();
            $table->string('link_text')->nullable();
            $table->boolean('layout')->default(1);
            $table->boolean('display_order')->default(0);
            $table->boolean('is_active')->default(0);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('updated_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onUpdate("cascade")->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('account_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('existing_record_id')->references('id')->on('account_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_types');
    }
}
