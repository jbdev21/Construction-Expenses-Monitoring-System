<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('payee');
            $table->unsignedBigInteger('personnel_id')->nullable();
            $table->unsignedBigInteger('petty_cash_id')->nullable();
            $table->string('description')->nullable();
            $table->double('amount');
            $table->string('type')->default('debit');
            $table->date('effectivity_date');
            $table->double('onhand_amount')->nullable();
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
        Schema::dropIfExists('ledgers');
    }
}
