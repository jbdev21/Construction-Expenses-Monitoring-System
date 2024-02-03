<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('contract_id');
            $table->double('contral_amount');
            $table->date('ntp_date');
            $table->integer('project_duration_years');
            $table->integer('project_duration_months');
            $table->integer('project_duration_days');
            $table->date('expiry_date');
            $table->text('contractor_licence');
            $table->unsignedBigInteger('category_id');
            $table->enum('status', ['ongoing', 'draft', 'finished'])->default('ongoing');
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
        Schema::dropIfExists('projects');
    }
}
