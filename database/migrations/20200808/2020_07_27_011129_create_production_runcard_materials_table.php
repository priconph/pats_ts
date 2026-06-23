<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionRuncardMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_runcard_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_no');
            $table->string('lot_no');
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->unsignedBigInteger('runcard_id');
            $table->unsignedBigInteger('pats_material_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();

            // Foreign Key
            $table->foreign('runcard_id')->references('id')->on('production_runcards');
            $table->foreign('pats_material_id')->references('id')->on('materials');
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
        Schema::dropIfExists('production_runcard_materials');
    }
}
