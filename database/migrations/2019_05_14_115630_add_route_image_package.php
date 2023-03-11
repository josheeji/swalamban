<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRouteImagePackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function($table) {
                $table->text('route_image')->nullable()->default(null);
                $table->text('route_map')->nullable()->default(null);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('packages', function($table) {
        $table->dropColumn('route_image')->nullable();
        $table->dropColumn('route_map')->nullable();
      });
    }
}
