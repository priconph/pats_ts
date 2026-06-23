<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsPrepSubKittingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_preps_sub_kitting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('sub_kittings_id');
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->text('remarks')->nullable();

            $table->unsignedInteger('received_by')->nullable();
            $table->unsignedInteger('received_passed_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
            $table->dateTime('received_at')->nullable();
            $table->dateTime('received_passed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            // $table->foreign('verified_by')->references('id')->on('users');
            // $table->foreign('checked_by_prod')->references('id')->on('users');
            // $table->foreign('checked_by_qc')->references('id')->on('users');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_preps_sub_kitting');
    }
}
