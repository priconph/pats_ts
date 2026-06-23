<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEPilotRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_pilot_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fkid_a');
            $table->string('po_no');
            $table->string('efinal_dispo')->comment = "1 - Go, 2 - Hold";
            $table->unsignedBigInteger('final_by_engr')->nullable();
            $table->datetime('econf_date')->nullable();
            $table->string('qfinal_dispo')->nullable()->comment = "1 - Go, 2 - Hold";
            $table->unsignedBigInteger('final_by_qc')->nullable();
            $table->datetime('qconf_date')->nullable();
            $table->string('created_by');
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
            $table->timestamps();    

            // Foreign Keys
            $table->foreign('fkid_a')->references('id')->on('a_pilot_runs');
            $table->foreign('final_by_engr')->references('id')->on('users');
            $table->foreign('final_by_qc')->references('id')->on('users');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_pilot_runs');
    }
}
