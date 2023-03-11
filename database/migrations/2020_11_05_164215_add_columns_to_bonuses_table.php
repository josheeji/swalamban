<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonuses', function (Blueprint $table) {
            $table->string('shareholder_no')->nullable()->after('category_id');
            $table->string('fathers_name')->nullable()->after('type');
            $table->string('grandfathers_name')->nullable()->after('fathers_name');
            $table->string('searchable_fathers_name')->nullable()->after('fathers_name');
            $table->string('searchable_grandfathers_name')->nullable()->after('grandfathers_name');
            $table->string('address')->nullable()->after('tax_amount');
            $table->string('total')->nullable()->after('actual_bonus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropColumn('shareholder_no');
            $table->dropColumn('fathers_name');
            $table->dropColumn('grandfathers_name');
            $table->dropColumn('searchable_fathers_name');
            $table->dropColumn('searchable_grandfathers_name');
            $table->dropColumn('address');
            $table->dropColumn('total');
        });
    }
}
