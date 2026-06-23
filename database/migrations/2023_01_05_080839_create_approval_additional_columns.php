<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalAdditionalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_flows', function (Blueprint $table) {
            $table->tinyInteger('approval_status')->nullable()->default(0)->comment = "1-Engineer, 2-Conformed by Prod Supervisor, 3-Approved by QC, 4-Disapproved by Prod Supervisor, 5-Disapproved by QC";
            $table->string('approval_remarks')->nullable();
            $table->string('approval_date')->nullable()->comment = "Last date of approved/disapproved";
            $table->string('approved_by')->nullable();
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
