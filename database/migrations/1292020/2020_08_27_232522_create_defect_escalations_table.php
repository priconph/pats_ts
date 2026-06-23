<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefectEscalationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defect_escalations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_no', 25);
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->string('defect_escalation_no', 25)->unique();
            $table->unsignedBigInteger('sub_station_id');
            $table->unsignedBigInteger('assembly_line_id');
            $table->unsignedBigInteger('operator');
            $table->string('pair_no', 100);
            $table->string('die_no', 100);
            $table->string('mold', 100);
            $table->string('product_type', 100);
            $table->string('product_type2', 100);
            $table->unsignedBigInteger('production')->nullable()->default(null);
            $table->unsignedBigInteger('engineering')->nullable()->default(null);
            $table->unsignedBigInteger('lqc')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('operator')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
            $table->foreign('production')->references('id')->on('users');
            $table->foreign('engineering')->references('id')->on('users');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
            $table->foreign('assembly_line_id')->references('id')->on('assembly_lines');
            $table->foreign('lqc')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('defect_escalations');
    }
}
