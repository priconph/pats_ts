<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_flows', function (Blueprint $table) {
            $table->string('material_lot_no')->nullable();
            $table->string('material_part_no')->nullable();
            $table->string('material_part_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_flows', function (Blueprint $table) {
            //
        });
    }
}
