<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialApprovalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_flows', function (Blueprint $table) {
            $table->tinyInteger('material_approval_status')->nullable()->default(0)->comment = "1-Prod Supervisor, 2-Conformed by Engineer, 3-Approved by QC, 4-Disapproved by Engineer, 5-Disapproved by QC";
            $table->string('material_approval_remarks')->nullable();
            $table->string('material_approval_date')->nullable()->comment = "Last date of approved/disapproved";
            $table->string('material_approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_flows', function (Blueprint $table) {
            //
        });
    }
}
