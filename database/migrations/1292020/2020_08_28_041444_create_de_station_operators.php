<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeStationOperators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_station_operators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('defect_escalation_id');
            $table->unsignedBigInteger('defect_escalation_station_id');
            $table->unsignedBigInteger('operator_id');
            
            // Foreign Keys
            $table->foreign('defect_escalation_id')->references('id')->on('defect_escalations');
            $table->foreign('operator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('de_station_operators');
    }
}
