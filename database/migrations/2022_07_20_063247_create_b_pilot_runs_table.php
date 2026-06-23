<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBPilotRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_pilot_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fkid_a');
            $table->string('po_no');
            $table->integer('term_length')->comment = "0 - Ok, 1 - NG, 2 - N/A";
            $table->string('term_length_checkedby')->nullable();
            $table->datetime('term_length_datetime')->nullable();
            $table->integer('measurement_result')->comment = "0 - Ok, 1 - NG, 2 - N/A";
            $table->string('measurement_result_checkedby')->nullable();
            $table->datetime('measurement_result_datetime')->nullable();
            $table->integer('dimension')->comment = "0 - Sampling, 1 - 100% Measurement, 2 - With Special Req";
            $table->string('dimension_with_specialReq')->nullable();
            $table->integer('vir')->comment = "0 - Ok, 1 - NG";
            $table->string('vir_remarks')->nullable();
            $table->string('highlights')->nullable();
            $table->integer('feedback_report')->comment = "0 - PTIS, 1 - NG Report, 2 - SAR, 3 - Others";
            $table->string('ptis_ctlno')->nullable();
            $table->string('ng_ctlno')->nullable();
            $table->string('sar_ctlno')->nullable();
            $table->string('other_ctlno')->nullable();

            $table->string('created_by');
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
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
        Schema::dropIfExists('b_pilot_runs');
    }
}
