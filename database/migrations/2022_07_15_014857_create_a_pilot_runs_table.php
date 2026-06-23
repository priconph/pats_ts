<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAPilotRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_pilot_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_no');
            $table->integer('po_qty');
            $table->string('series_name');
            $table->integer('status')->comment = "0 - pending, 1 - Regular";
            $table->integer('pair_no');
            $table->integer('control_type')->comment = "0 - Special, 1 - Regular";
            $table->integer('special_control_type');
            $table->integer('special_other_type')->nullable();
            $table->string('1_akl_drawing')->nullable();
            $table->string('1_akl_drawing_rev')->nullable();
            $table->string('1_akl_drawing_page')->nullable();
            $table->string('1_akl_drawing_checkedby')->nullable();
            $table->string('1_akl_drawing_remarks')->nullable();
            $table->string('1_gag_drawing')->nullable();
            $table->string('1_gag_drawing_rev')->nullable();
            $table->string('1_gag_drawing_page')->nullable();
            $table->string('1_gag_drawing_checkedby')->nullable();
            $table->string('1_gag_drawing_remarks')->nullable();
            $table->string('1_packdoc_drawing')->nullable();
            $table->string('1_packdoc_drawing_rev')->nullable();
            $table->string('1_packdoc_drawing_page')->nullable();
            $table->string('1_packdoc_drawing_checkedby')->nullable();
            $table->string('1_packdoc_drawing_remarks')->nullable();
            $table->string('1_packspecs_drawing')->nullable();
            $table->string('1_packspecs_drawing_rev')->nullable();
            $table->string('1_packspecs_drawing_page')->nullable();
            $table->string('1_packspecs_drawing_checkedby')->nullable();
            $table->string('1_packspecs_drawing_remarks')->nullable();
            $table->string('1_otud_drawing')->nullable();
            $table->string('1_otud_drawing_rev')->nullable();
            $table->string('1_otud_drawing_page')->nullable();
            $table->string('1_otud_drawing_checkedby')->nullable();
            $table->string('1_otud_drawing_remarks')->nullable();
            $table->string('1_wi_drawing')->nullable();
            $table->string('1_wi_drawing_rev')->nullable();
            $table->string('1_wi_drawing_page')->nullable();
            $table->string('1_wi_drawing_checkedby')->nullable();
            $table->string('1_wi_drawing_remarks')->nullable();
            $table->integer('2_prov_parts_req')->comment = "1 - Yes, 2 - No";
            $table->string('2_prov_parts_req_checkedby')->nullable();
            $table->string('2_prov_parts_req_remarks')->nullable();
            $table->integer('2_assbly_parts_req')->comment = "1 - Yes, 2 - No";
            $table->string('2_assbly_parts_checkedby')->nullable();
            $table->string('2_assbly_parts_remarks')->nullable();
            $table->integer('2_withacc_parts_req')->comment = "1 - Yes, 2 - No";
            $table->integer('2_withacc_parts_req_no')->nullable();
            $table->string('2_withacc_parts_req_checkedby')->nullable();
            $table->string('2_withacc_parts_req_remarks')->nullable();
            $table->integer('3_assblt_type')->comment = "0 - Single, 1 - Set";
            $table->string('3_assblt_type_checkedby')->nullable();
            $table->string('3_assblt_type_remarks')->nullable();
            $table->string('3_parts_printing')->nullable();
            $table->string('3_parts_printing_no')->nullable();
            $table->string('3_parts_printing_checkedby')->nullable();
            $table->string('3_parts_printing_remarks')->nullable();
            $table->string('3_char_printed')->nullable();
            $table->string('3_ref_doc_akl_drawing')->nullable();
            $table->string('3_ref_doc_gag_drawing')->nullable();
            $table->integer('3_gluing_req')->comment = "1 - With, 2 - Without";
            $table->string('3_gluing_req_checkedby')->nullable();
            $table->string('3_gluing_req_remarks')->nullable();
            $table->integer('3_loctite_req')->comment = "1 - With, 2 - Without";
            $table->string('3_loctite_req_checkedby')->nullable();
            $table->string('3_loctite_req_remarks')->nullable();
            $table->integer('3_hvt_req')->comment = "1 - With, 2 - Without";
            $table->string('3_hvt_req_checkedby')->nullable();
            $table->string('3_hvt_req_remarks')->nullable();
            $table->integer('3_etest_req')->comment = "1 - With, 2 - Without";
            $table->string('3_etest_req_checkedby')->nullable();
            $table->string('3_etest_req_remarks')->nullable();
            $table->integer('3_gauge_req')->comment = "1 - With, 2 - Without";
            $table->string('3_gauge_ctl_no')->nullable();
            $table->string('3_gauge_req_checkedby')->nullable();
            $table->string('3_gauge_req_remarks')->nullable();
            $table->integer('3_need_planarity')->comment = "1 - Yes, 2 - No";
            $table->string('3_need_planarity_checkedby')->nullable();
            $table->string('3_need_planarity_remarks')->nullable();

            $table->string('created_by');
            $table->integer('logdel')->comment = "0 - Active, 1 - Inactive";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_pilot_runs');
    }
}
