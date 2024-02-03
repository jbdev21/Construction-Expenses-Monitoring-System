<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccomplishmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accomplishment_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("project_id");
            $table->string("type")->default("item"); // item or group
            $table->text("name");
            $table->string("item_number")->nullable();
            $table->string("unit")->nullable(); // this is mostly percentage
            $table->double("weight")->nullable(); // this is mostly percentage
            $table->double("quantity")->nullable();
            $table->double("revised_contract_quantity")->nullable();
            $table->double("unit_cost")->nullable();
            $table->double("total_contract_cost")->nullable(); // this is upon update
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
        Schema::dropIfExists('accomplishment_items');
    }
}
