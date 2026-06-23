<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapVerificationRuncardItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_verification_runcard_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('scrap_verification_runcard_id');
            $table->unsignedInteger('wbs_kit_issuance_id');
            $table->unsignedTinyInteger('wbs_table')->default(0)->comment = '1=tbl_wbs_kit_issuance,2=tbl_wbs_sakidashi_issuance';
            $table->unsignedTinyInteger('mod')->default(0)->comment = '0-na';
            $table->text('remarks')->nullable();

            $table->unsignedTinyInteger('ng_qty_setup')->default(0);
            $table->unsignedTinyInteger('ng_qty_prod')->default(0);
            $table->unsignedTinyInteger('ng_qty_mat')->default(0);

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('scrap_verification_runcard_id')->references('id')->on('scrap_verification_runcard');
            // $table->foreign('verified_by')->references('id')->on('users');
            // $table->foreign('checked_by_prod')->references('id')->on('users');
            // $table->foreign('checked_by_qc')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scrap_verification_runcard_items');
    }
}
