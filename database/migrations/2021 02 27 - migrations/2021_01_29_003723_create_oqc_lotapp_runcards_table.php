<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcLotappRuncardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_lotapp_runcards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('oqc_lotapp_id');
            $table->string('runcard_no');
            $table->integer('output_qty');
            $table->string('applied_by')->comment = "ID, not employee id from users table";
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
        Schema::dropIfExists('oqc_lotapp_runcards');
    }
}
