<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateADetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('a_pilot_runs', function (Blueprint $table) {
            $table->string('1_engr_confirmation')->nullable();
            $table->string('1_engr_confirmation_due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('a_pilot_runs', function (Blueprint $table) {
            //
        });
    }
}
