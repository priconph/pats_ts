<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackinginspectionNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packinginspection_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_num');
            $table->string('packing_code');
            $table->integer('total_lot_qty');
            $table->integer('anti_rust_inclusion')->nullable()->comment = "1 - With, 2 - Without";
            $table->integer('packing_type')->nullable()->comment = "1 - Box, 2 - Tray, 3 - Cylinder, 4 - Pallet Case";
            $table->integer('unit_condition')->nullable()->comment = "1 - Terminal Mounted on Esafoam, 2 - Terminal Down, 3 - Terminal - Up";
            $table->datetime('packing_inspection_datetime')->nullable();
            $table->integer('prelim_inspector_id')->nullable()->comment = "ID from RapidX PATS";
            $table->integer('final_packop_conformance')->nullable()->comment = "ID from Rapidx PATS";
            $table->datetime('final_packop_datetime')->nullable();
            $table->integer('final_packop_inspector_id')->nullable()->comment = "ID from Rapidx PATS";
            $table->integer('coc_attachment')->nullable()->comment = "1 - yes, 2 - No, 3 - N/a";
            $table->integer('result')->nullable()->comment = "1 - No Defect Found, 2 - Defect Found";
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->integer('logdel')->comment = "0 - Active, 2 - Inactive";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packinginspection_new');
    }
}
