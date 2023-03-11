<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable()->default(null);
            $table->string('middle_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('activation_token')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('blocked')->default(0);
            $table->boolean('closed')->default(0);
            $table->timestamp('last_login_at');
            $table->ipAddress('last_login_ip')->nullable();
            $table->rememberToken();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
