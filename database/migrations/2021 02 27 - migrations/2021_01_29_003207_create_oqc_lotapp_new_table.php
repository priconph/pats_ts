<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcLotappNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_lotapp_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_num');
            $table->string('oqc_lotapp_id');
            $table->string('created_by')->comment = "ID, not employee id from users table";
            $table->integer('device_type')->comment = "1 - Automotive, 2 - Regular";
            $table->integer('assembly_line_id')->nullable()->comment = "id from table assembly_lines";
            $table->string('applied_by')->nullable()->comment = "ID, not employee id from users table";
            $table->string('application_datetime')->nullable();
            $table->integer('status')->comment = "1 - Runcards Added, 2 - Lot Applied, 3 - Returned by OQC VIR, for 2nd Submission, 4 - 2nd Sub Applied, 5 - Accepted, 6 - Rejected";
            $table->timestamps();
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oqc_lotapp_new');
    }
}
