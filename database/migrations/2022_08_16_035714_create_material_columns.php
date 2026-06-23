<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prod_runcard_station_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_station_id');
            $table->integer('material_id');
            $table->string('material_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('material_type')->nullable();
            $table->tinyInteger('is_deleted')->default(0)->comment = "0-no, 1-yes";

            // Foreign Key
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
        Schema::table('prod_runcard_station_materials', function (Blueprint $table) {
            //
        });
    }
}
