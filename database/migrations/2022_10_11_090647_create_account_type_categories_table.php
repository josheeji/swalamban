<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_type_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('existing_record_id')->nullable();
            $table->boolean('language_id')->default(1);
            $table->string('title');
            $table->string('slug');
            $table->string('banner')->nullable();
            $table->string('image')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->boolean('display_order')->default(0);
            $table->boolean('is_active')->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('existing_record_id')->references('id')->on('account_type_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onUpdate("cascade")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_type_categories');
    }
};