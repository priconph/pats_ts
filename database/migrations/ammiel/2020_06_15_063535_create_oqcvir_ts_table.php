<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcvirTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqcvir_ts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('po_num');
            $table->string('lot_batch_no');
            $table->string('packing_code');
            $table->unsignedTinyInteger('status')->comment = '0 = On-going inspection, 1 = Done OK, 2 = NG';
            $table->string('oqc_sample');
            $table->string('ok_qty');
            $table->string('ng_qty');
            $table->dateTime('insp_stime');
            $table->dateTime('insp_etime');

            $table->tinyInteger('use_template')->comment = "1 - yes, 2 - no, 3 - n/a";
            $table->tinyInteger('yd_requirement')->comment = "1 - with, 2 - without";
            $table->tinyInteger('csh_coating')->comment = "1 - yes, 2 - no, 3 - n/a";
            $table->unsignedTinyInteger('acc_req')->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('coc_req')->comment = '1-Yes,2-No';

            $table->string('insp_name')->comment = 'ID of who did the inspection';

            $table->unsignedTinyInteger('result')->comment = '1-NO DEFECT FOUND,2-WITH DEFECT FOUND / DETAILS';
            $table->unsignedTinyInteger('judgement')->comment = '1-ACCEPTED,2-REJECTED';
            $table->string('remarks')->nullable();

            $table->string('empid');

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
        Schema::dropIfExists('oqcvir_ts');
    }
}
