<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrControlNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_pilot_runs', function (Blueprint $table) {
            $table->string('chk_ctl_no')->nullable();
            $table->string('pr_ctl_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *php 
     * @return void
     */
    public function down()
    {
        Schema::table('c_pilot_runs', function (Blueprint $table) {
            //
        });
    }
}
