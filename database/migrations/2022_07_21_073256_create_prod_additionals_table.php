<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            $table->integer('qty_input')->nullable()->comment = "Additional for pilot run requirements";
            $table->integer('qty_output')->nullable()->comment = "Additional for pilot run requirements";
            $table->integer('qty_ng')->nullable()->comment = "Additional for pilot run requirements";
            $table->string('disposition')->nullable()->comment = "Additional for pilot run requirements";
            $table->unsignedBigInteger('mod')->nullable()->comment = "Additional for pilot run requirements";
            $table->unsignedBigInteger('qc_id')->nullable()->comment = "Additional for pilot run requirements";
            $table->string('judgement')->nullable()->comment = "Additional for pilot run requirements, 1-First Out, 2-Last Out, 3-Mass Prod, 4-Prod Quali";

            $table->foreign('qc_id')->references('id')->on('users');
            $table->foreign('mod')->references('id')->on('mode_of_defects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            //
        });
    }
}
