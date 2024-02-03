<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldExpensesColumnInProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->double('old_expense_labors')->default(0);
            $table->double('old_expense_materials')->default(0);
            $table->double('old_expense_rentals')->default(0);
            $table->double('old_expense_others')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('old_expense_labors');
            $table->dropColumn('old_expense_materials');
            $table->dropColumn('old_expense_rentals');
            $table->dropColumn('old_expense_others');
        });
    }
}
