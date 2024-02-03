<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accomplishment_items', function (Blueprint $table) {
            $table->double("revised_unit_cost")->nullable();
            $table->double("revised_contract_cost")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accomplishment_items', function (Blueprint $table) {
            $table->dropColumn("revised_unit_cost")->after("total_contract_cost");
            $table->dropColumn("revised_contract_cost")->after("revised_unit_cost");
        });
    }
};
