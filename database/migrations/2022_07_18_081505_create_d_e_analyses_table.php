<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDEAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_analyses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('defect_escalation_id');
            $table->string('c_analysis')->nullable();
            $table->string('c_action_taken')->nullable();
            $table->string('c_when')->nullable();
            $table->string('c_remarks')->nullable();
            $table->string('c_production')->nullable();
            $table->string('c_production_disposition')->nullable();
            $table->string('c_engineering')->nullable();
            $table->string('c_engineering_disposition')->nullable();
            $table->string('c_qc')->nullable();
            $table->string('c_qc_disposition')->nullable();

            $table->string('d_action_taken')->nullable();
            $table->string('d_when')->nullable();
            $table->string('d_remarks')->nullable();
            $table->string('d_production_manager')->nullable();
            $table->string('d_production_manager_disposition')->nullable();
            $table->string('d_qc_manager')->nullable();
            $table->string('d_qc_manager_disposition')->nullable();
            $table->string('d_ppc_head')->nullable();
            $table->string('d_ppc_head_disposition')->nullable();
            $table->string('d_engineering_manager')->nullable();
            $table->string('d_engineering_manager_disposition')->nullable();
            $table->string('d_technical_adviser')->nullable();
            $table->string('d_technical_adviser_disposition')->nullable();

            $table->string('e_action_taken')->nullable();
            $table->string('e_when')->nullable();
            $table->string('e_remarks')->nullable();

            $table->string('e_vice_president')->nullable();
            $table->string('e_vice_president_disposition')->nullable();
            $table->string('e_president')->nullable();
            $table->string('e_president_disposition')->nullable();

            $table->string('status')->default(1)->comment = "0 - Inactive, 1 - Active";
            $table->unsignedBigInteger('created_by');
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
            $table->timestamps();

            // Foreign Keys
            $table->foreign('defect_escalation_id')->references('id')->on('defect_escalations');
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
        Schema::dropIfExists('de_analyses');
    }
}
