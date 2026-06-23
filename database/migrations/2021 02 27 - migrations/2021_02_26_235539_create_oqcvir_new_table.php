<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcvirNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqcvir_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_num');
            $table->string('oqc_lotapp_id');
            $table->unsignedTinyInteger('status')->comment = '0 = On-going inspection, 1 = Done OK, 2 = NG';
            $table->string('oqc_sample')->nullable();
            $table->string('ok_qty')->nullable();
            $table->string('ng_qty')->nullable();
            $table->dateTime('insp_stime')->nullable();
            $table->dateTime('insp_etime')->nullable();

            $table->tinyInteger('use_template')->nullable()->comment = "1 - cdyes, 2 - no, 3 - n/a";
            $table->tinyInteger('yd_requirement')->nullable()->comment = "1 - with, 2 - without";
            $table->tinyInteger('csh_coating')->nullable()->comment = "1 - yes, 2 - no, 3 - n/a";
            $table->unsignedTinyInteger('acc_req')->nullable()->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('coc_req')->nullable()->comment = '1-Yes,2-No';

            $table->string('insp_name')->nullable()->comment = 'ID of who did the inspection';

            $table->unsignedTinyInteger('result')->nullable()->comment = '1-NO DEFECT FOUND,2-WITH DEFECT FOUND / DETAILS';
            $table->unsignedTinyInteger('judgement')->nullable()->comment = '1-ACCEPTED,2-REJECTED';
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
        Schema::dropIfExists('oqcvir_new');
    }
}
