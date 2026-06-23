<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReworkOpt2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('de_reworks', function (Blueprint $table) {
            $table->string('rework_code2')->nullable();
            $table->unsignedBigInteger('operator2')->nullable();

            $table->foreign('operator2')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('de_reworks', function (Blueprint $table) {
            //
        });
    }
}
