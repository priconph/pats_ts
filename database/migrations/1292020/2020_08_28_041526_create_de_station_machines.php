<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeStationMachines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_station_machines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('defect_escalation_id');
            $table->unsignedBigInteger('defect_escalation_station_id');
            $table->unsignedBigInteger('machine_id');
            
            // Foreign Keys
            $table->foreign('defect_escalation_id')->references('id')->on('defect_escalations');
            // $table->foreign('defect_escalation_station_id')->references('id')->on('defect_escalation_stations'); //Connection not working, an error occured during migration.
            $table->foreign('machine_id')->references('id')->on('machines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('de_station_machines');
    }
}
