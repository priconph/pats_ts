<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('defect_escalation_id');
            $table->unsignedBigInteger('sub_station_id');
            $table->integer('step_num');
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->integer('qty_good')->nullable();
            $table->integer('qty_ng')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('defect_escalation_id')->references('id')->on('defect_escalations');
            // $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('de_stations');
    }
}
