<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id')->nullable()->default(null);
            $table->string('title')->nullable()->default(null);
            $table->string('caption')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('link')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('display_order')->default(1);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('admins');
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->foreign('deleted_by')->references('id')->on('admins');

            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_banners');
    }
}
