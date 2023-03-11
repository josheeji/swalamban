<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJsonLdSeoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('seos', function($table) {
              $table->text('json_description')->nullable();
              $table->string('slug')->nullable()->change();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('seos', function($table) {
        $table->dropColumn('json_description')->nullable();
      });
    }
}
