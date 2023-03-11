<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('slug', '300');
            $table->text('image')->nullable()->default(null);
            $table->text('cover_image')->nullable()->default(null);
            $table->text('description');
            $table->string('duration');
            $table->boolean('is_active')->nullable()->default(null);
            $table->text('trip_overview')->nullable()->default(null);
            $table->text('itinerary_details')->nullable()->default(null);
            $table->text('includes_excludes')->nullable()->default(null);
            $table->string('food')->nullable()->default(null);
            $table->string('difficulty')->nullable()->default(null);
            $table->string('accommodation')->nullable()->default(null);
            $table->string('start_end')->nullable()->default(null);
            $table->string('max_altitude')->nullable()->default(null);
            $table->string('transportation')->nullable()->default(null);
            $table->string('best_season')->nullable()->default(null);
            $table->text('cost')->nullable()->default(null);
            $table->text('tac')->nullable()->default(null);
            $table->unsignedInteger('display_order')->default(1);

            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);

            $table->foreign('created_by')->references('id')->on('admins');
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->foreign('deleted_by')->references('id')->on('admins');

            $table->nullableTimestamps();
            $table->softDeletes();
            $table->unsignedInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('destinations')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id')->references('id')->on('activities')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
