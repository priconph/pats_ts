<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackinginspectionNewLotapps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packinginspection_new_lotapps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('packing_code')->comment = "packing code from packinginspection_new";
            $table->integer('oqclotapp_id')->comment = "id from oqcvir";
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
        Schema::dropIfExists('packinginspection_new_lotapps');
    }
}
