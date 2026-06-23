<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartialAndRemarksColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_runcard_stations2', function (Blueprint $table) {
            $table->integer('partial')->nullable()->comment = "0 - No, 1 - Yes";
            $table->longText('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_runcard_stations2', function (Blueprint $table) {
            //
        });
    }
}
