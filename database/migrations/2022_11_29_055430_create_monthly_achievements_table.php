<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("project_id");
            $table->double("weight")->default(0); // percentage result
            $table->double("earned_weight")->default(0); // percentage result
            $table->string("month");
            $table->integer("year");
            $table->string("complete_month_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_achievements');
    }
}
