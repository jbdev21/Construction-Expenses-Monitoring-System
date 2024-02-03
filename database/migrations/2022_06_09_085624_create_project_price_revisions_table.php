<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPriceRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_price_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->double("amount");
            $table->string("type")->default("debit"); // debit or credit
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
        Schema::dropIfExists('project_price_revisions');
    }
}
