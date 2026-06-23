<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDPilotRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_pilot_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fkid_a');
            $table->string('po_no')->nullable();
            $table->string('1p_ini_check')->nullable();
            $table->string('1p_fin_check')->nullable();
            $table->string('1e_ini_check')->nullable();
            $table->string('1e_fin_check')->nullable();
            $table->string('1q_ini_check')->nullable();
            $table->string('1q_fin_check')->nullable();
            $table->string('1_remarks')->nullable();
            $table->string('2p_ini_check')->nullable();
            $table->string('2p_fin_check')->nullable();
            $table->string('2e_ini_check')->nullable();
            $table->string('2e_fin_check')->nullable();
            $table->string('2q_ini_check')->nullable();
            $table->string('2q_fin_check')->nullable();
            $table->string('2_remarks')->nullable();
            $table->string('3p_ini_check')->nullable();
            $table->string('3p_fin_check')->nullable();
            $table->string('3e_ini_check')->nullable();
            $table->string('3e_fin_check')->nullable();
            $table->string('3q_ini_check')->nullable();
            $table->string('3q_fin_check')->nullable();
            $table->string('3_remarks')->nullable();
            $table->string('4p_ini_check')->nullable();
            $table->string('4p_fin_check')->nullable();
            $table->string('4e_ini_check')->nullable();
            $table->string('4e_fin_check')->nullable();
            $table->string('4q_ini_check')->nullable();
            $table->string('4q_fin_check')->nullable();
            $table->string('4_remarks')->nullable();
            $table->string('5p_ini_check')->nullable();
            $table->string('5p_fin_check')->nullable();
            $table->string('5e_ini_check')->nullable();
            $table->string('5e_fin_check')->nullable();
            $table->string('5q_ini_check')->nullable();
            $table->string('5q_fin_check')->nullable();
            $table->string('5_remarks')->nullable();
            $table->string('6p_ini_check')->nullable();
            $table->string('6p_fin_check')->nullable();
            $table->string('6e_ini_check')->nullable();
            $table->string('6e_fin_check')->nullable();
            $table->string('6q_ini_check')->nullable();
            $table->string('6q_fin_check')->nullable();
            $table->string('6_remarks')->nullable();
            $table->string('7p_ini_check')->nullable();
            $table->string('7p_fin_check')->nullable();
            $table->string('7e_ini_check')->nullable();
            $table->string('7e_fin_check')->nullable();
            $table->string('7q_ini_check')->nullable();
            $table->string('7q_fin_check')->nullable();
            $table->string('7_remarks')->nullable();
            $table->string('8p_ini_check')->nullable();
            $table->string('8p_fin_check')->nullable();
            $table->string('8e_ini_check')->nullable();
            $table->string('8e_fin_check')->nullable();
            $table->string('8q_ini_check')->nullable();
            $table->string('8q_fin_check')->nullable();
            $table->string('8_remarks')->nullable();
            $table->unsignedBigInteger('prodn_checked_by')->nullable();
            $table->date('prdn_datetime')->nullable();
            $table->unsignedBigInteger('eng_checked_by')->nullable();
            $table->date('eng_datetime')->nullable();
            $table->unsignedBigInteger('qc_checked_by')->nullable();
            $table->date('qc_datetime')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
            $table->timestamps();

            // Foreign Keys
            $table->foreign('fkid_a')->references('id')->on('a_pilot_runs');
            $table->foreign('prodn_checked_by')->references('id')->on('users');
            $table->foreign('eng_checked_by')->references('id')->on('users');
            $table->foreign('qc_checked_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_pilot_runs');
    }
}
