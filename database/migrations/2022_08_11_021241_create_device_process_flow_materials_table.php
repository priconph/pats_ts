<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceProcessFlowMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_process_flow_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('process_flow_id');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('sub_station_id');
            $table->integer('material_id');
            $table->string('material_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('material_type')->nullable();
            $table->tinyInteger('status')->default(1)->comment = "0-inactive, 1-active";
            $table->tinyInteger('is_deleted')->default(0)->comment = "0-no, 1-yes";

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();

            // Foreign Key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->foreign('process_flow_id')->references('id')->on('process_flows');
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_process_flow_materials');
    }
}
