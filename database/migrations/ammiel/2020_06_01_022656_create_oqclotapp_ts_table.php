<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqclotappTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqclotapp_ts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('status')->comment = '0 - Prodn Approval, 1 - QC Approval, 2 - Done';
            $table->string('po_num');
            $table->string('lot_batch_no');
            $table->tinyInteger('submission');
            $table->tinyInteger('device_cat')->comment = '1 - Automotive, 2 - Non-Automotive';
            $table->tinyInteger('assy_line');
            $table->integer('lot_qty');

            $table->string('empid');
            $table->string('prod_supervisor');
            $table->string('oqc_supervisor');


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
        Schema::dropIfExists('oqclotapp_ts');
    }
}
