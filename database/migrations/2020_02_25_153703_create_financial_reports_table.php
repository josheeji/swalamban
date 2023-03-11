<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('existing_record_id')->nullable();
            $table->boolean('language_id')->nullable()->default(1);
            $table->unsignedInteger('category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('file')->nullable();
            $table->boolean('display_order')->nullable()->default(1);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('existing_record_id')->references('id')->on('financial_reports')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('financial_report_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('financial_reports');
    }
}
