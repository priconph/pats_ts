<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingInspectionTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing_inspection_ts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_num');
            $table->string('device_code');
            $table->float('total_lot_qty');
            $table->integer('anti_rust_inclusion');
            $table->integer('packing_type')->nullable();
            $table->integer('packing_unit_condition')->nullable();
            $table->datetime('packing_inspection_datetime')->nullable();
            $table->string('prelim_oqc_inspector_id')->nullable();
            $table->string('packing_code')->nullable();


            //4.2 Final QC Packing Inspection Datetime
            $table->string('final_packing_operator_id')->nullable();
            $table->string('final_oqc_inspector_id')->nullable();
            $table->datetime('final_packing_inspection_datetime')->nullable();
            $table->integer('coc_attachment')->nullable();
            $table->text('final_remarks')->nullable();

            $table->integer('status')->comment = "1 - Confirmed Lots, 2 - Preliminary Packing QC Inspection OK, 3 - Final Packing Inspection OK, 4 - Final Packing Inspection NG";

            $table->timestamps();
            $table->integer('logdel')->default(1)->comment = "1 - Active, 0 - Inactive";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packing_inspection_ts');
    }
}
