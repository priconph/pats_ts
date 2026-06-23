<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapVerificationRuncardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_verification_runcard', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wbs_kit_issuance_id');
            $table->unsignedTinyInteger('wbs_table')->default(0)->comment = '1=tbl_wbs_kit_issuance,2=tbl_wbs_sakidashi_issuance';
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-verified';
            $table->text('remarks')->nullable();

            $table->unsignedTinyInteger('product_line')->default(0)->comment = '0-na';

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('verified_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
            $table->dateTime('verified_at')->nullable();
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
        Schema::dropIfExists('scrap_verification_runcard');
    }
}
