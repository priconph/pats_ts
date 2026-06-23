<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalDDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('d_pilot_runs', function (Blueprint $table) {
            $table->string('4p_actual_char')->nullable();
            $table->string('4e_actual_char')->nullable();
            $table->string('4q_actual_char')->nullable();
            $table->string('4_actual_char_rem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('d_pilot_runs', function (Blueprint $table) {
            //
        });
    }
}
