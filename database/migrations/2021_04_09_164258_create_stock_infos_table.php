<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->unsignedInteger('language_id')->default(1);
            $table->string('paidup_value')->nullable();
            $table->string('maximum')->nullable();
            $table->string('minimum')->nullable();
            $table->string('closing')->nullable();
            $table->string('traded_share')->nullable();
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('existing_record_id')->references('id')->on('stock_infos')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('stock_infos');
    }
}
