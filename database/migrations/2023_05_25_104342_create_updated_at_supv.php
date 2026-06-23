<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdatedAtSupv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('de_reworks', function (Blueprint $table) {
            $table->datetime('prod_upAt')->nullable();
            $table->datetime('engg_upAt')->nullable();
            $table->datetime('qc_upAt')->nullable();
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
