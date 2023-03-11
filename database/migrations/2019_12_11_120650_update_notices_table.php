<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->unsignedInteger('existing_record_id')->after('id')->nullable();
            $table->string('reference_id')->nullable()->after('existing_record_id');
            $table->boolean('visible_in')->default(1)->after('slug');
            $table->boolean('type')->default(1)->after('slug');
            $table->string('excerpt')->nullable()->after('slug');
            $table->text('description')->nullable()->after('excerpt');
            $table->unsignedInteger('created_by');

            $table->dropUnique(['slug']);

            $table->foreign('created_by')->references('id')->on('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('existing_record_id')->references('id')->on('notices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['existing_record_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('type');
            $table->dropColumn('visible_in');
            $table->dropColumn('reference_id');
            $table->dropColumn('description');
            $table->dropColumn('excerpt');
            $table->dropColumn('existing_record_id');
            $table->unique('slug');
        });
    }
}
