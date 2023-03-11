<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_type_id')->unsigned();
            $table->string('first_name')->nullable()->default(null);
            $table->string('middle_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('activation_token')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('blocked')->default(0);
            $table->boolean('closed')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->rememberToken();
            $table->nullableTimestamps();

            $table->foreign('admin_type_id')
                ->references('id')->on('admin_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
