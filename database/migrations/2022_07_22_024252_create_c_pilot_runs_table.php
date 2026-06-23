<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCPilotRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_pilot_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fkid_a');
            $table->string('po_no')->nullable();
            $table->string('prdn_potential_failure')->nullable();
            $table->string('prdn_potential_failure_others')->nullable();
            $table->string('prdn_affected_parts')->nullable();
            $table->string('prdn_action_taken')->nullable();
            $table->string('prdn_orcto')->nullable();
            $table->string('prdn_tools_jigs')->nullable();
            $table->string('prdn_docs')->nullable();
            $table->date('prdn_due_date')->nullable();
            // $table->string('eng_potential_failure')->nullable();
            // $table->string('eng_potential_failure_others')->nullable();
            // $table->string('eng_affected_parts')->nullable();
            // $table->string('eng_action_taken')->nullable();
            // $table->string('eng_orcto')->nullable();
            // $table->string('eng_tools_jigs')->nullable();
            // $table->string('eng_docs')->nullable();
            // $table->string('eng_due_date')->nullable();
            $table->unsignedBigInteger('prdn_validated_by')->nullable();
            $table->date('prdn_datetime')->nullable();
            $table->unsignedBigInteger('eng_validated_by')->nullable();
            $table->date('eng_datetime')->nullable();
            $table->unsignedBigInteger('qc_validated_by')->nullable();
            $table->date('qc_datetime')->nullable();
            $table->string('highlights')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
            $table->timestamps();

            // Foreign Keys
            $table->foreign('fkid_a')->references('id')->on('a_pilot_runs');
            $table->foreign('prdn_validated_by')->references('id')->on('users');
            $table->foreign('eng_validated_by')->references('id')->on('users');
            $table->foreign('qc_validated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c_pilot_runs');
    }
}
