<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeReworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_reworks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit_no');
            $table->unsignedBigInteger('defect_escalation_id');
            $table->unsignedBigInteger('mode_of_defect_id');
            $table->string('location_of_ng');
            $table->integer('ng_qty');
            $table->unsignedTinyInteger('scrap')->default(0)->comment = '1-YES,0-NO';
            $table->unsignedTinyInteger('for_rework')->default(0)->comment = '1-YES,0-NO';
            $table->unsignedTinyInteger('for_verification')->default(0)->comment = '1-YES,0-NO';

            $table->unsignedTinyInteger('prod')->default(1)->comment = '1-GOOD,0-NG';
            $table->unsignedTinyInteger('engg')->default(1)->comment = '1-GOOD,0-NG';
            $table->unsignedTinyInteger('qc')->default(1)->comment = '1-GOOD,0-NG';

            $table->integer('rework_qty')->nullable();

            $table->integer('result_qty_ok')->nullable();
            $table->integer('result_qty_scrap')->nullable();

            $table->string('rework_code')->nullable();
            $table->string('terminal_gauge')->nullable();
            $table->string('dummy_lo')->nullable();
            $table->string('dummy_mo')->nullable();

            $table->unsignedBigInteger('operator')->nullable();
            $table->date('date')->nullable();

            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->timestamps();

            // Foreign Key
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->foreign('defect_escalation_id')->references('id')->on('defect_escalations');
            $table->foreign('mode_of_defect_id')->references('id')->on('mode_of_defects');
            $table->foreign('operator')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('de_reworks');
    }
}
