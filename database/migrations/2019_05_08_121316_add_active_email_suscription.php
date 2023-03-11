<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveEmailSuscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('email_subscriptions', function($table) {
            $table->boolean('is_active')->nullable()->default(null);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_subscriptions', function($table) {
        $table->dropColumn('is_active')->nullable();
      });
    }
}
