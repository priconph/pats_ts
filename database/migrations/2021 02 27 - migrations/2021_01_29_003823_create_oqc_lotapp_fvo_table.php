<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcLotappFvoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_lotapp_fvo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('oqc_lotapp_id');
            $table->string('fvo_user_id')->comment = "ID, not employee id from users table";
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
        Schema::dropIfExists('oqc_lotapp_fvo');
    }
}
