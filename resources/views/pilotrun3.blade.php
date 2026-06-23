@auth
  @php
    if(Auth::user()->position == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 2){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 3){
      $layout = 'layouts.mh_user_layout';
    }
    else if(Auth::user()->position == 4){
      $layout = 'layouts.production_user_layout';
    }
    else if(Auth::user()->position == 5){
      $layout = 'layouts.ipqc_user_layout';
    }
    else if(Auth::user()->position == 6){
      $layout = 'layouts.whse_layout';
    }
    else if(Auth::user()->position == 7){
      $layout = 'layouts.ppc_layout';
    }
    else if(Auth::user()->position == 8){
      $layout = 'layouts.clerk_layout';
    }
    else if(Auth::user()->position == 9){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->position == 10){
      $layout = 'layouts.oqc_layout';
    }
    else if(Auth::user()->position == 11){
      $layout = 'layouts.technician_user_layout';
    }
    else if(Auth::user()->position == 12){
      $layout = 'layouts.admin_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'Pilot Run3')

  @section('content_page')
  <style type="text/css">
    .hidden_scanner_input{
       position: fixed;
      opacity: 0;
    }
    .modal-xl-custom{
      width: 95%!important;
      min-width: 95%!important;
    }
    .modal-footer {
      justify-content: space-between;
    }

    /* #product-assembly{
      position: relative;
    }

    #btnShowAddCriticalFailure{
      position: absolute;
      top: 10%;
      right: 5%;
    } */
</style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1>C. Product / Assembly Checking and Potential Assembly Failure Analysis <br> (In-Charge: PRODUCTION / ENGINEERING / QC)</h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search PO Number</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3">
                    <label>PO Number</label>
                   <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_po_number" placeholder="Scan PO Code." disabled>
                    </div>

                   <!--  <div class="input-group">
                      <input type="text" class="form-control" id="txt_po_number">
                    </div> -->
                  </div>
                  <div class="col-sm-3">
                    <label>Device Name</label>
                    <input type="text" class="form-control" id="txt_device_name_lbl" readonly>
                  </div>
                  <div class="col-sm-2">
                    <label>Device Code</label>
                   <input type="text" class="form-control" id="txt_device_code_lbl" readonly>
                  </div>
                  <div class="col-sm-1">
                    <label>PO Qty</label>
                    <input type="text" class="form-control" id="txt_po_qty_lbl" readonly>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">C. Product / Assembly Checking and Potential Assembly Failure Analysis Summary</h3>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tbl_pilot_runC" style="min-width: 900px;">
                      <thead>
                        <tr class="bg-light">
                        <th>Action</th>
                        <th>Status</th>
                        <th>Control Type</th>
                        <th>Pair No.</th>
                        <th>Created At</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="modalPilotRunDetailsC" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> C - Pilot Run Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>

          <!-- TEMPORARY LOCATION -->
          {{-- <div class="col-sm-6"> --}}
            {{-- <button class="btn btn-primary btn-sm" id="btnShowAddCriticalFailure"><i class="fa fa-plus"></i> Add Critical Failure</button> --}}
            <br>
            <br>
          {{-- </div> --}}

          <form id="frm_pilotrun_Cdetails" method="post">
            @csrf
            <div class="modal-body">

              <center>
                <h3>PILOT RUN EVALUATION REPORT</h3>
              </center>

              <input type="hidden" name="global_pilotrun_id" id="global_pilotrun_id">
              <input type="hidden" name="global_pilotrun_status" id="global_pilotrun_status">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_series_name" name="txt_series_name" style="color: blue; font-weight: bold;" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Num</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_num" name="txt_po_num" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_qty" name="txt_po_qty" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Pair No/s.</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_pair_no" name="txt_pair_no" readonly>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Start Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_start_datetime" name="txt_start_datetime" readonly="true" placeholder="Auto generated">
                    </div>
                  </div>
                </div>

                <div class="accordion" id="accordionMain">
                  <!-- A -->
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          A. MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION)
                        </button>
                      </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMain">
                      <div class="card-body">

                      <div class="col-sm-8">
                        TYPE OF CONTROL / QUALIFICATION / ACTIVITY TO PERFORM

                        <div class="row">
                          <div class="col-sm-6 rdo_control">
                            <input type="radio" id="rdo_special_control" name="control_type" value="1">
                              &nbsp;&nbsp;&nbsp;&nbsp;<b class="rdo_special_control">Special Control (Conduct Pilot Run) - check items A to E)</b>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_1" name="special_control_type" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_1">New Series (with MD)</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_2" name="special_control_type" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_2">Long period of no prod'n</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_3" name="special_control_type" value="3">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_3">New Device (With MD)</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_4" name="special_control_type" value="4">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_4">Manual Pulling</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_5" name="special_control_type" value="5">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_5">New Product</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="radio" id="rdo_special_control_6" name="special_control_type" value="6">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_control_6">Others</i>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-6">
                            <b>Others</b>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_1" name="special_other_type" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_1">Toshiba/Kioxia Customer</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_2" name="special_other_type" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_2">With Customer Claim</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_3" name="special_other_type" value="3">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_3">Micron Customer</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_4" name="special_other_type" value="4">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_4">With Lot-out</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_5" name="special_other_type" value="5">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_5">YEU Customer <br>(MAVERICK & QUALCOMM)</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_6" name="special_other_type" value="6">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_6">Contribution of Yield off-target</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_7" name="special_other_type" value="7">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_7">AYE Customer</i>
                              </div>
                              <div class="col-sm-6">
                                <input type="checkbox" id="rdo_special_other_8" name="special_other_type" value="8">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_8">With UD or Special Instructions</i>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-12">
                                <input type="checkbox" id="rdo_special_other_9" name="special_other_type" value="9">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_special_other_9">SMPO (QFP/TSOP/Socket 2/PS) <br>with P.O. quantity of 99 pieces and below</i>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-8">
                            <input type="radio" id="rdo_regular_control" name="control_type" value="2">
                              &nbsp;&nbsp;&nbsp;&nbsp;<b class="rdo_regular_control">Regular Control (Conduct Product qualification) - check items A, B, & E) </b><br>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(All Series other than specified above)</b>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <span class="badge badge-warning">
                            SAMPLE SIZE / DEFINITION:
                        </span>
                        <br>
                            <b>SPECIAL CONTROL:</b> 5 PCS. / Shall wait for the qualification result prior mass production <br>
                            <b>REGULAR CONTROL:</b> 1 PC. / Can proceed to mass production even without waiting the qualification result
                      </div>
                      <div class="col-sm-12">
                        <span class="badge badge-warning">
                            NOTE:
                        </span>
                        <br>
                            1. Fill in the blanks with the necessary informations. Check (a) the appropriate box. Put N/A if not applicable.<br>
                            2. The above conditions will still comply with what is stated in the Machine Qualification Standard Procedure (PQS-I01-027) & SMPO Line Procedure (PPS-D02-001).<br>
                            3. The scope of Customer Claim, Lot-out and Yield off-target is up to 3 years record. Records are saved in TS_Data_PAST TROUBLE HISTORY.<br>
                             4. Special Control – are critical products / with special requirement, critical customers & with quality issues; Regular Control – are non-critical & have no quality issues
                      </div>
                      <br>

                      <div class="col-sm-12">
                        <span class="badge badge-primary">A.</span> <b> MATERIAL / PRODUCT / PROCESS REQUIREMENT CHECKING (IN-CHARGE: PRODUCTION) </b>
                        <br>
                        PRODUCT DRAWING VS. MATERIAL LIST / OTHER REQUIRED DOCUMENTS AND REFERENCES
                        <br>
                      </div>
                      <div class="col-sm-12">
                        <span class="badge badge-warning">1.</span> PRODUCT DRAWING REVISION CHECKING
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_AKLdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.1 A / KL Drawing #</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_AKLdrawing" name="txt_AKLdrawing" readonly>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_AKLdrawing_rev" name="txt_AKLdrawing_rev" placeholder="REV" readonly>
                                <input type="text" class="form-control" id="txt_AKLdrawing_page" name="txt_AKLdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_AKLdrawing_pn" name="hidden_AKLdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_AKLdrawing_pn" name="txt_AKLdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_AKLdrawing_pn" data-checked-by-hidden="hidden_AKLdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_AKLdrawing_remarks" name="txt_AKLdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_GAGdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.2 G / AG Drawing #</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_GAGdrawing" name="txt_GAGdrawing" readonly>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_GAGdrawing_rev" name="txt_GAGdrawing_rev" placeholder="REV" readonly>
                                <input type="text" class="form-control" id="txt_GAGdrawing_page" name="txt_GAGdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_GAGdrawing_pn" name="hidden_GAGdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_GAGdrawing_pn" name="txt_GAGdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_GAGdrawing_pn" data-checked-by-hidden="hidden_GAGdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_GAGdrawing_remarks" name="txt_GAGdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row row_container">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_PackDocdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.3 Packing Doc # (ex. PM,DJ,etc)</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">

                               <input type="text" class="form-control class_txt_PackDocdrawing" list="list_txt_PackDocdrawing" id="txt_PackDocdrawing" name="txt_PackDocdrawing" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                                <datalist class="class_dl_PackDocdrawing" id="list_txt_PackDocdrawing"></datalist>

                                <!-- <input type="text" class="form-control" id="txt_PackDocdrawing" name="txt_PackDocdrawing" readonly> -->
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_PackDocdrawing_rev" name="txt_PackDocdrawing_rev" placeholder="REV" readonly>
                                <input type="text" class="form-control" id="txt_PackDocdrawing_page" name="txt_PackDocdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_PackDocdrawing_pn" name="hidden_PackDocdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_PackDocdrawing_pn" name="txt_PackDocdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_PackDocdrawing_pn" data-checked-by-hidden="hidden_PackDocdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_PackDocdrawing_remarks" name="txt_PackDocdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_PackSpecsdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.4 Packing Specs (MD Doc #)</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_PackSpecsdrawing" name="txt_PackSpecsdrawing">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_PackSpecsdrawing_rev" name="txt_PackSpecsdrawing_rev" placeholder="REV">
                                <input type="text" class="form-control" id="txt_PackSpecsdrawing_page" name="txt_PackSpecsdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_PackSpecsdrawing_pn" name="hidden_PackSpecsdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_PackSpecsdrawing_pn" name="txt_PackSpecsdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-toggle="modal" data-checked-by="txt_PackSpecsdrawing_pn" data-checked-by-hidden="hidden_PackSpecsdrawing_pn" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_PackSpecsdrawing_remarks" name="txt_PackSpecsdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_OTUDdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.5 OT/Urgent Direction #</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_OTUDdrawing" name="txt_OTUDdrawing">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_OTUDdrawing_rev" name="txt_OTUDdrawing_rev" placeholder="REV">
                                <input type="text" class="form-control" id="txt_OTUDdrawing_page" name="txt_OTUDdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_OTUDdrawing_pn" name="hidden_OTUDdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_OTUDdrawing_pn" name="txt_OTUDdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_OTUDdrawing_pn" data-checked-by-hidden="hidden_OTUDdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_OTUDdrawing_remarks" name="txt_OTUDdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                      <button style="width:30px" type="button" class="btn btn-sm py-0 btn-info table-btns" id="btnView_WIDdrawing">
                                        <i class="fa fa-file" title="View"></i>
                                      </button>
                                  <span class="input-group-text w-100" id="basic-addon1">1.6 Work Instruction # (For Provisional Insertion Only)</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_WIDdrawing" name="txt_WIDdrawing">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_WIDdrawing_rev" name="txt_WIDdrawing_rev" placeholder="REV">
                                <input type="text" class="form-control" id="txt_WIDdrawing_page" name="txt_WIDdrawing_page" placeholder="NO. OF PAGE">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="hidden" class="form-control" id="hidden_WIDdrawing_pn" name="hidden_WIDdrawing_pn" placeholder="CHECKED BY" readonly>
                                <input type="text" class="form-control" id="txt_WIDdrawing_pn" name="txt_WIDdrawing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_WIDdrawing_pn" data-checked-by-hidden="hidden_WIDdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_WIDdrawing_remarks" name="txt_WIDdrawing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                      </div>
                      <br>

                      <div class="col-sm-12">
                        <span class="badge badge-warning">2.</span> PARTS CHECKING
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">2.1 Provisional Insertion Parts Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_provi_req_yes" name="provi_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_provi_req_yes">YES</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_provi_req_no" name="provi_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_provi_req_no">NO</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_provi_req_pn" name="hidden_provi_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_provi_req_pn" name="txt_provi_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_provi_req_pn" data-checked-by-hidden="hidden_provi_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_provi_req_remarks" name="txt_provi_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">2.2 Assembly Parts Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_assmbly_req_yes" name="assmbly_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_assmbly_req_yes">YES</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_assmbly_req_no" name="assmbly_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_assmbly_req_no">NO</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_assmbly_req_pn" name="hidden_assmbly_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_assmbly_req_pn" name="txt_assmbly_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_assmbly_req_pn" data-checked-by-hidden="hidden_assmbly_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_assmbly_req_remarks" name="txt_assmbly_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">2.3 With Accessory Parts Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_with_accessory_req_yes" name="with_accessory_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_with_accessory_req_yes">YES</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_with_accessory_req_no" name="with_accessory_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_with_accessory_req_no">NO</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" id="txt_with_accessory_req_no" name="txt_with_accessory_req_no" placeholder="NO. OF ACCESSORY">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_with_accessory_req_pn" name="hidden_with_accessory_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_with_accessory_req_pn" name="txt_with_accessory_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_with_accessory_req_pn" data-checked-by-hidden="hidden_with_accessory_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_with_accessory_req_remarks" name="txt_with_accessory_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- nmodify --}}
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">2.4 Parts appearance and design (PPD parts only) &nbsp;<b>Prod'n</b></span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_prodn_yes" name="parts_app_design_prodn" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_prodn_yes">YES</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_prodn_no" name="parts_app_design_prodn" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_prodn_no">NO</i>
                              </div>

                              <div class="col-sm-2">
                                <div class="input-group input-group-sm mb-2">
                                  <!-- Newly Added -->
                                  <input type="hidden" class="form-control" id="hidden_parts_app_design_prodn_pn" name="hidden_parts_app_design_prodn_pn" readonly>
                                  <input type="text" class="form-control" id="txt_parts_app_design_prodn_pn" name="txt_parts_app_design_prodn_pn" placeholder="Checked by - Auto generated" readonly>
                                  {{-- btnScanProdnSupv --}}
                                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnPartsAppDesignProdn" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                                  <div class="input-group-prepend">
                                    <!-- <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_with_accessory_req_pn" data-checked-by-hidden="hidden_with_accessory_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button> -->
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_parts_app_design_prodn_remarks" name="txt_parts_app_design_prodn_remarks" placeholder="REMARKS">
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">Note: Will be checked by Prodn, Eng'g & QC Staff&nbsp;<b>Eng'g:</b></span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_engg_yes" name="parts_app_design_engg" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_engg_yes">YES</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_engg_no" name="parts_app_design_engg" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_engg_no">NO</i>
                              </div>
                              <div class="col-sm-2">
                                <div class="input-group input-group-sm mb-2">
                                  <!-- Newly Added -->
                                  <input type="hidden" class="form-control" id="hidden_parts_app_design_engg_pn" name="hidden_parts_app_design_engg_pn" readonly>
                                  <input type="text" class="form-control" id="txt_parts_app_design_engg_pn" name="txt_parts_app_design_engg_pn" placeholder="Checked by - Auto generated" readonly>
                                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnPartsAppDesignEngg" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                                  <div class="input-group-prepend">
                                    <!-- <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_parts_app_design_engg_pn" data-checked-by-hidden="hidden_parts_app_design_engg_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button> -->
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_parts_app_design_engg_remarks" name="txt_parts_app_design_engg_remarks" placeholder="REMARKS">
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">F1 - grind parts only / F3 - all mold/grinded parts &nbsp;<b>QC:</b></span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_qc_yes" name="parts_app_design_qc" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_qc_yes">YES</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="parts_app_design_qc_no" name="parts_app_design_qc" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="parts_app_design_qc_no">NO</i>
                              </div>
                              <div class="col-sm-2">
                                <div class="input-group input-group-sm mb-2">
                                  <!-- Newly Added -->
                                  <input type="hidden" class="form-control" id="hidden_parts_app_design_qc_pn" name="hidden_parts_app_design_qc_pn" readonly>
                                  <input type="text" class="form-control" id="txt_parts_app_design_qc_pn" name="txt_parts_app_design_qc_pn" placeholder="Checked by - Auto generated" readonly>
                                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnPartsAppDesignQC" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                                  <div class="input-group-prepend">
                                    <!-- <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_parts_app_design_qc_pn" data-checked-by-hidden="hidden_parts_app_design_qc_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button> -->
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_parts_app_design_qc_remarks" name="txt_parts_app_design_qc_remarks" placeholder="REMARKS">
                                </div>
                              </div>
                          </div>
                      </div>
                      <br>

                      <div class="col-sm-12">
                        <span class="badge badge-warning">3.</span> PRODUCT / ASSEMBLY / PROCESS REQUIREMENT CHECKING
                        <div class="row">
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">3.1 Assembly Type</span>
                                </div>
                                 <select class="form-control form-control-sm" id="txt_assembly_type" name="txt_assembly_type">
                                  <option selected disabled>-- Choose One --</option>
                                  <option value="0">Single</option>
                                  <option value="1">Set</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_assembly_type_pn" name="hidden_assembly_type_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_assembly_type_pn" name="txt_assembly_type_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_assembly_type_pn" data-checked-by-hidden="hidden_assembly_type_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-7">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_assembly_type_remarks" name="txt_assembly_type_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">3.2 Parts for Printing</span>
                                </div>
                                <input type="text" class="form-control" id="txt_parts_printing" name="txt_parts_printing">
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">No. of Printing Required</span>
                                </div>
                                <input type="number" class="form-control" id="txt_printing_req" name="txt_printing_req">
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_parts_printing_pn" name="hidden_parts_printing_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_parts_printing_pn" name="txt_parts_printing_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_parts_printing_pn" data-checked-by-hidden="hidden_parts_printing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_parts_printing_remarks" name="txt_parts_printing_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-3">
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">Character to be printed</span>
                                </div>
                                <input type="text" class="form-control" id="txt_char_printed" name="txt_char_printed">
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">Page of reference docs. where printing was indicated</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_page_ref_AKLdrawing" name="txt_page_ref_AKLdrawing" placeholder="A / KL Drawing">
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_page_ref_GAGdrawing" name="txt_page_ref_GAGdrawing" placeholder="G / AG Dawing">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.3 Gluing Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_gluing_req_with" name="gluing_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_gluing_req_with">WITH</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_gluing_req_without" name="gluing_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_gluing_req_without">WITHOUT</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_gluing_req_pn" name="hidden_gluing_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_gluing_req_pn" name="txt_gluing_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_gluing_req_pn" data-checked-by-hidden="hidden_gluing_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_gluing_req_remarks" name="txt_gluing_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.4 Loctite Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_loctite_req_with" name="loctite_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_loctite_req_with">WITH</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_loctite_req_without" name="loctite_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_loctite_req_without">WITHOUT</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_loctite_req_pn" name="hidden_loctite_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_loctite_req_pn" name="txt_loctite_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_loctite_req_pn" data-checked-by-hidden="hidden_loctite_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_loctite_req_remarks" name="txt_loctite_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.5 HVT Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_hvt_req_with" name="hvt_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_hvt_req_with">WITH</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_hvt_req_without" name="hvt_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_hvt_req_without">WITHOUT</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_hvt_req_pn" name="hidden_hvt_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_hvt_req_pn" name="txt_hvt_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_hvt_req_pn" data-checked-by-hidden="hidden_hvt_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_hvt_req_remarks" name="txt_hvt_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.6 E-TEST Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_etest_req_with" name="etest_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_etest_req_with">WITH</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_etest_req_without" name="etest_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_etest_req_without">WITHOUT</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_etest_req_pn" name="hidden_etest_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_etest_req_pn" name="txt_etest_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_etest_req_pn" data-checked-by-hidden="hidden_etest_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_etest_req_remarks" name="txt_etest_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.7 Gauge Requirement</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_gauge_req_with" name="gauge_req" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_gauge_req_with">WITH</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_gauge_req_without" name="gauge_req" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_gauge_req_without">WITHOUT</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_gauge_req_pn" name="hidden_gauge_req_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_gauge_req_pn" name="txt_gauge_req_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_gauge_req_pn" data-checked-by-hidden="hidden_gauge_req_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_gauge_req_remarks" name="txt_gauge_req_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                          </div>
                          <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                              <div class="input-group-prepend w-50">
                                <span class="input-group-text w-100" id="basic-addon1">Gauge Control No.</span>
                              </div>
                              <input type="text" class="form-control" id="txt_gauge_control_no" name="txt_gauge_control_no">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3.8 Need Planarity checking using gauge?</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_need_planarity_yes" name="need_planarity" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_need_planarity_yes">YES</i>
                            </div>
                            <div class="col-sm-1">
                              <input type="radio" id="rdo_need_planarity_no" name="need_planarity" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_need_planarity_no">NO</i>
                            </div>
                            <div class="col-sm-2">
                              <div class="input-group input-group-sm mb-2">
                                <!-- Newly Added -->
                                <input type="hidden" class="form-control" id="hidden_need_planarity_pn" name="hidden_need_planarity_pn" placeholder="CHECKED BY" readonly>

                                <input type="text" class="form-control" id="txt_need_planarity_pn" name="txt_need_planarity_pn" placeholder="CHECKED BY" readonly>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_need_planarity_pn" data-checked-by-hidden="hidden_need_planarity_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="input-group input-group-sm mb-2">
                                <input type="text" class="form-control" id="txt_need_planarity_remarks" name="txt_need_planarity_remarks" placeholder="REMARKS">
                              </div>
                            </div>
                        </div>
                      </div>

                      </div>
                    </div>

                  </div>

                  <!-- B -->
                  <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                          B. UNIT, N = 1 VS. PRODUCT DRAWING AND OTHER REFERENCES (IN-CHARGE: QC)
                        </button>
                      </h5>
                    </div>

                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionMain">
                      <div class="card-body">

                      <div class="row">
                        <div class="col-sm-6">
                          <b>CHECKPOINTS</b>
                          <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">1. TERMINAL LENGTH</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_term_length_ok" name="term_length" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_term_length_ok">OK</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_term_length_ng" name="term_length" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_term_length_ng">NG</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_term_length_na" name="term_length" value="3">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_term_length_na">N/A</i>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <input type="hidden" class="form-control" id="hidden_term_length_pn" name="hidden_term_length_pn" placeholder="QC NAME" readonly>
                                  <input type="text" class="form-control" id="txt_term_length_pn" name="txt_term_length_pn" placeholder="QC NAME" readonly>
                                  <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_term_length_pn" data-checked-by-hidden="hidden_term_length_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control" id="txt_term_length_date" name="txt_term_length_date" placeholder="Date/Time Auto generated" readonly>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">2. MEASUREMENT RESULTS</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_measure_result_ok" name="measure_result" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_measure_result_ok">OK</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_measure_result_ng" name="measure_result" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_measure_result_ng">NG</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_measure_result_na" name="measure_result" value="3">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_measure_result_na">N/A</i>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <input type="hidden" class="form-control" id="hidden_measure_result_pn" name="hidden_measure_result_pn" placeholder="QC NAME" readonly>
                                  <input type="text" class="form-control" id="txt_measure_result_pn" name="txt_measure_result_pn" placeholder="QC NAME" readonly>
                                  <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_measure_result_pn" data-checked-by-hidden="hidden_measure_result_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control" id="txt_measure_result_date" name="txt_measure_result_date" placeholder="Date/Time Auo generated" readonly>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100" id="basic-addon1">3. DIMENSION</span>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-2">
                              <input type="radio" id="rdo_dimension_type_1" name="dimension_type" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_dimension_type_1">Sampling</i>
                            </div>
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_dimension_type_2" name="dimension_type" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_dimension_type_2">100% Measurement</i>
                            </div>
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_dimension_type_3" name="dimension_type" value="3">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_dimension_type_3">With Special Req.</i>
                            </div>
                            <div class="col-sm-1">
                              <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_special_req" name="txt_special_req">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                                <div class="input-group input-group-sm mb-2">
                                  <div class="input-group-prepend w-100">
                                    <span class="input-group-text w-100" id="basic-addon1">4. VISUAL INSPECTION RESULT</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_vir_ok" name="vir" value="1">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_vir_ok">OK</i>
                              </div>
                              <div class="col-sm-1">
                                <input type="radio" id="rdo_vir_ng" name="vir" value="2">
                                  &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_vir_ng">NG</i>
                              </div>
                              <div class="col-sm-7">
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control" id="txt_vir_remarks" name="txt_vir_remarks" placeholder="REMARKS">
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <b>HIGHLIGHTS</b>
                          <textarea class="form-control form-control-sm" id="txt_highlights" name="txt_highlights" rows="7"></textarea>
                        </div>
                        <div class="col-sm-4">
                          <b>FEEDBACK REPORT ISSUANCE / CONTROL NO.</b>
                          <div class="row">
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_ptis" name="feedback_report" value="1">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_ptis">PTIS</i>
                            </div>
                            <div class="col-sm-7">
                              <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_ptis" name="txt_ptis">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_ng_report" name="feedback_report" value="2">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_ng_report">NG REPORT</i>
                            </div>
                            <div class="col-sm-7">
                              <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_ng_report" name="txt_ng_report">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_sar" name="feedback_report" value="3">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_sar">SAR</i>
                            </div>
                            <div class="col-sm-7">
                              <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_sar" name="txt_sar">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-3">
                              <input type="radio" id="rdo_others" name="feedback_report" value="4">
                                &nbsp;&nbsp;&nbsp;&nbsp;<i class="rdo_others">OTHERS</i>
                            </div>
                            <div class="col-sm-7">
                              <div class="input-group input-group-sm mb-2">
                                  <input type="text" class="form-control" id="txt_others" name="txt_others">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      </div>
                    </div>
                  </div>


                  <!-- C -->
                  <div class="row">
                    <div class="col-md-6" id="product-assembly">
                      <span class="badge badge-primary">C.</span> <b> PRODUCT / ASSEMBLY CHECKING AND POTEMTIAL ASSEMBLY FAILURE ANALYSIS (IN-CHARGE: PRODUCTION / ENGINEERING / QC)</b>
                      <br>
                      IDENTIFY POTENTIAL CRITICAL FAILURES (CHECKING OF MATERIALS AND ASSEMBLY PROCESS VS. PRODUCT DRAWING AND OTHER REFERENCES)
                      <br>
                    </div>

                    <div class="col-md-6">
                      <button class="btn btn-primary btn-sm float-right m-1" id="btnShowAddCriticalFailure"><i class="fa fa-plus"></i> Add Critical Failure</button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover small" id="tblAssemblyAnalysis" style="min-width: 900px;">
                          <thead>
                            <tr align="center">
                              <th colspan="4" class="alert-info" style="text-align: center;" ><b>IN-CHARGE: PRODUCTION / ENGINEERING</b></th>
                              <th colspan="4" class="alert-info" style="text-align: center;" ><b>IN-CHARGE: PRODUCTION / ENGINEERING</b></th>
                              <th colspan="4" class="alert-info" style="text-align: center;" ><b>IN-CHARGE: ENGINEERING</b></th>
                              <th colspan="3" class="alert-warning" style="text-align: center;"><b>IN-CHARGE: QC</b></th>
                            </tr>
                            <tr align="center">
                              <th rowspan="2" class="alert-info">ACTION</th>
                              <th rowspan="2" class="alert-info">CONTROL #</th>
                              <th rowspan="2" class="alert-info">POTENTIAL FAILURES</th>
                              <th rowspan="2" class="alert-info">AFFECTED PARTS</th>
                              <th colspan="4" class="alert-info" style="text-align: center;"><b>SHORT TERM ACTION/S</b></th>
                              <th colspan="4" class="alert-info" style="text-align: center;"><b>LONG TERM ACTION/S</b></th>
                              <th rowspan="2" class="alert-warning">VALIDATED BY</th>
                              <th rowspan="2" class="alert-warning">DATE</th>
                              <th rowspan="2" class="alert-warning">HIGHLIGHTS</th>
                            </tr>
                            <tr align="center">
                              <th class="alert-info">ACTION TAKEN</th>
                              <th class="alert-info">ORCTO</th>
                              <th class="alert-info">DUE DATE</th>
                              <th class="alert-info">PRODUCTION</th>
                              <th class="alert-info">TOOLS/JIGS</th>
                              <th class="alert-info">DOCUMENTS (PP/WI/SEI)</th>
                              <th class="alert-info">DUE DATE</th>
                              <th class="alert-info">ENGINEERING</th>

                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <div class="modal-footer">
                <div>
                <div class="col-sm-12">
                  <b> TERMS:</b> &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>UD</b> - Urgent Directions &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>PP</b> - Point Panel &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>WI</b> - Work Instruction &nbsp;&nbsp;&nbsp;&nbsp;
                      <b>ORCTO</b> - Operations Record of Conformance for Training / Orientation
                </div>
                </div>
                <div class="actions">
                  <button type="submit" id="id_btn_save_pilotC" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
                  <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>

         </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="mdlSaveCriticalFailures" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-puzzle-piece text-info"></i> Potential Failure Details</h5>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
            <div class="col-md-6">
              <button class="btn btn-primary btn-sm float-right m-1" id="btnView_gotoRapid"><i class="fa fa-file"></i> GO TO RAPID ACDCS</button>
            </div>

          </div>

          <div class="modal-body">
            <form id="frmSaveCriticalFailures" method="post">
              @csrf

              <!-- Hidden Ids -->
              <div class="col">
                <input type="text" style="display: none" class="form-control form-control-sm" id="global_pilotrun_id" name="global_pilotrun_id">
                <input type="text" style="display: none" class="form-control form-control-sm" id="global_pilotrun_po" name="global_pilotrun_po">
                <input type="text" style="display: none" class="form-control form-control-sm" id="pilotRunCId" name="pilot_run_c_id">
              </div>

              <div class="col mb-2">
                <span class="badge badge-warning">IN-CHARGE: PRODUCTION / ENGINEERING</span>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input input-group-text" type="checkbox" id="pr_ctl_checkbox" name="pr_ctl_checkbox" value="1">
                      <span class="form-check-label input-group-text" for="pr_ctl_checkbox">Control Number</span>
                    </div>
                  </div>
                  <input type="text" class="form-control" id="txt_pr_control_no" name="txt_pr_control_no" placeholder="Pilot Run Control #" readonly>
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Potential Failures</span>
                  </div>
                  <select class="form-control form-control-sm" id="txt_pot_fail" name="txt_pot_fail">
                    <option selected disabled>-- Choose One --</option>
                    <option value="1">1 .Wrong Orientation/location of parts</option>
                    <option value="2">2. Wrong pin pattern/population</option>
                    <option value="3">3. Wrong pin orientation</option>
                    <option value="4">4. Missing pin (with erratic pattern & pin at the center)</option>
                    <option value="5">5. Missing parts</option>
                    <option value="6">6. Easily broken</option>
                    <option value="7">7. Stuck-up parts</option>
                    <option value="8">8. Loose parts</option>
                    <option value="9">9. Hard to install parts</option>
                    <option value="10">10. Hard to install parts</option>
                    <option value="11">11. Dislocated parts</option>
                    <option value="12">12. Other</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Affected Parts</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txt_aff_parts" name="txt_aff_parts">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Initial Taken</span>
                  </div>
                  <textarea class="form-control form-control-sm" id="txt_action_taken" name="txt_action_taken" rows="3"></textarea>
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">ORCTO</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txt_orcto" name="txt_orcto">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Due Date</span>
                  </div>
                  <input type="date" class="form-control form-control-sm" id="txt_prod_due_date" name="txt_prod_due_date">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Production Name</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txtOperator" name="operator_name" readonly="true">
                  <input type="text" class="form-control form-control-sm" id="txtOperatorId" name="operator_id" readonly="" style="display: none;">
                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnScanOperator" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                </div>
              </div>
              <div class="col mb-2">
                <span class="badge badge-warning">IN-CHARGE: ENGINEERING</span><br>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Tools/Jigs</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txt_tools_jigs" name="txt_tools_jigs">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Documents (PP/WI/SEI)</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txt_docs" name="txt_docs">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Due Date</span>
                  </div>
                  <input type="date" class="form-control form-control-sm" id="txt_eng_due_date" name="txt_eng_due_date">
                </div>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Engineer Name</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txtEngineer" name="engineer_name" readonly="true">
                  <input type="text" class="form-control form-control-sm" id="txtEngineerId" name="engineer_id" readonly="" style="display: none;">
                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnScanEngineer" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                </div>
              </div>

              <div class="col mb-2">
                <span class="badge badge-warning">IN-CHARGE: QC</span><br>
              </div>
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Validated By</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txtQC" name="qc_name" readonly="true">
                  <input type="text" class="form-control form-control-sm" id="txtQCId" name="qc_id" readonly="" style="display: none;">
                  <button class="btn btn-xs btn-primary input-group-append btnScan" id="btnScanQC" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-barcode"></i></button>
                </div>
              </div>
              {{-- <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Date</span>
                  </div>
                  <input type="date" class="form-control form-control-sm" id="txt_val_date" name="txt_val_date" placeholder="Auto generated">
                </div>
              </div> --}}
              <div class="col">
                <div class="input-group input-group-sm mb-2">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Highlights</span>
                  </div>
                  <textarea class="form-control form-control-sm" id="txt_critical_highlights" name="txt_critical_highlights" rows="3"></textarea>
                </div>
              </div>

            <!-- </form> -->
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnSaveCriticalFailures" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
            <!-- <button type="button" class="btn btn-primary btn-sm" form="frmSaveCriticalFailures" id="btnSaveCriticalFailures">Save</button> -->
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>

          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="modalSearchProdn" modal-checked-by="" modal-checked-by-hidden="" tabindex="-1">
      <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
            Please scan your ID.
            <br>
            <br>
            <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_id" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
            Please scan your ID.
            <br><br>
            <!-- <h1><i class="fa fa-barcode fa-lg"></i></h1> -->
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input">
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>

   <!-- Modal Scan PO -->
    <div class="modal fade" id="modalScanPONum" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan the PO code.
              <br>
              <br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txtSearchPoNum" class="hidden_scanner_input">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  </div>
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let currentPoNo = "";
    let global_pilotrun_id = '';
    //txtGlobalPilotRunId
    let arrayProdnEmp = [];
    let arrayProdnEmpName = [];
    let dt_pilot_run;
    let dt_pilot_run_c;

  $(document).ready(function () {
    setTimeout(function() {
      $('.nav-link').find('.fa-bars').closest('a').click();
    }, 100);

    $('#txt_po_number').keypress(function(e){
      if(e.keyCode == 13){
        $(this).val( $(this).val().split(' ')[0] )
        // arrSelectedRuncards = [];
        $(".spanOICount").html('');
        currentPoNo = $(this).val();
        dt_pilot_run.draw();
        $('#txt_device_name_lbl').val('');
        $('#txt_device_code_lbl').val('');
        $('#txt_po_qty_lbl').val('');
        $('#txt_device_name').val('');

        $("#txt_AKLdrawing").val('');
        $("#txt_AKLdrawing_rev").val('');
        $("#txt_AKLdrawing_page").val('');

        $("#txt_GAGdrawing").val('');
        $("#txt_GAGdrawing_rev").val('');
        $("#txt_GAGdrawing_page").val('');

        $("#txt_PackDocdrawing").val('');
        $("#txt_PackDocdrawing_rev").val('');
        $("#txt_PackDocdrawing_page").val('');

        $(this).val('');
        $(this).focus();
      }
    });

    dt_pilot_run = $('#tbl_pilot_runC').DataTable({
      "processing" : true,
        "serverSide" : true,
        "ajax" : {
          url: "get_pilotrun_by_po",
          data: function (param){
              param.po_no = currentPoNo;
              param.status = [1,2,3,4,5,6,7,8,9];
          }
        },
        bAutoWidth: false,

        "columns":[
          { "data" : "raw_action", orderable:false, searchable:false },
          { "data" : "status_lbl" },
          { "data" : "control_type" },
          { "data" : "pair_no" },
          { "data" : "created_at" },
        ],
        "columnDefs": [
          {
            "targets": [1, 2],
            "data": null,
            "defaultContent": "--"
          },
        ],
        order: [[2, "desc"]],
        "rowCallback": function(row,data,index ){
          currentPoNo = data['po_no'];
          $('#txt_po_number').val( data['po_no'] );
          $('#txt_device_name_lbl').val( data['wbs_kitting']['device_name'] );
          $('#txt_po_qty_lbl').val( data['wbs_kitting']['txt_po_qty_lbl'] );
        },
        "drawCallback": function(row,data,index ){
          // GetMaterialKittingListByPoNo($(".selAccessoryName"), $('#txt_po_number').val());

          // $(".chkSelProdRuncard").each(function(index){
          //     if(arrSelectedRuncards.includes($(this).attr('production-runcard-id'))){
          //         $(this).attr('checked', 'checked');
          //     }
          // });

          // var api = this.api();
          // var rows = api.rows( {page:'current'} ).nodes();
          // var last = null;

          GetMaterialKitting();
        },
        paging: false,
    });

    dt_pilot_run_c = $('#tblAssemblyAnalysis').DataTable({
      "processing" : true,
        "serverSide" : true,
        "ajax" : {
          url: "view_pilotrun_c_details",
          data: function (param){
              param.id = $('input[name="global_pilotrun_id"]', $('#frm_pilotrun_Cdetails')).val();
          }
        },
        bAutoWidth: false,

        "columns":[
          { "data" : "raw_action", defaultContent: '--', orderable:false, searchable:false},
          { "data" : "pr_ctl_no", defaultContent: '--'},
          { "data" : "prdn_potential_failure", defaultContent: '--'},
          { "data" : "prdn_affected_parts", defaultContent: '--'},
          { "data" : "prdn_action_taken", defaultContent: '--'},
          { "data" : "prdn_orcto", defaultContent: '--'},
          { "data" : "prdn_due_date", defaultContent: '--'},
          { "data" : "prod_user_info.name", defaultContent: '--'},
          { "data" : "prdn_tools_jigs", defaultContent: '--'},
          { "data" : "prdn_docs", defaultContent: '--'},
          { "data" : "eng_due_date", defaultContent: '--'},
          { "data" : "eng_user_info.name", defaultContent: '--'},
          { "data" : "qc_user_info.name", defaultContent: '--'},
          { "data" : "qc_datetime", defaultContent: '--'},
          { "data" : "highlights", defaultContent: '--'},
        ],
        "columnDefs": [
          {
            "targets": [0,1,2,3,4,5,6,7,8,9],

            "defaultContent": "--"
          },
        ],
        order: [[1, "desc"]],
        "rowCallback": function(row,data,index ){

        },
        "drawCallback": function(row,data,index ){

        },
        paging: false,
    });

    function GetMaterialKitting(){
      $.ajax({
        url: "get_wbs_material_kitting_rev",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          boxing = "";
          assessment = "";
        },
        success: function(data){

          if(data['material_kitting'] != null){
            $("#txt_po_number").val(data['material_kitting']['po_no']);
            $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
            $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
            $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);

            if (data['orig_a_drawing'] ==''){
              $("#txt_AKLdrawing").val('N/A');
              $("#txt_AKLdrawing_rev").val('N/A');
              $('.btnView_AKLdrawing').prop('disabled', true);
            }else{
              $("#txt_AKLdrawing").val(data['orig_a_drawing'][0]['doc_no']);
              $("#txt_AKLdrawing_rev").val(data['orig_a_drawing'][0]['rev_no']);
              $("#txt_AKLdrawing_page").val(data['orig_a_drawing'][0]['page_no']);
            }

            if (data['g_drawing'] ==''){
              $("#txt_GAGdrawing").val('N/A');
              $("#txt_GAGdrawing_rev").val('N/A');
              $('.btnView_GAGdrawing').prop('disabled', true);
            }else{
              $("#txt_GAGdrawing").val(data['g_drawing'][0]['doc_no']);
              $("#txt_GAGdrawing_rev").val(data['g_drawing'][0]['rev_no']);
              $("#txt_GAGdrawing_page").val(data['g_drawing'][0]['page_no']);
            }

            if(data['material_kitting']['device_info'] != null){
              boxing = data['material_kitting']['device_info']['boxing'];
            }
            else{
              boxing = "";
            }

            if(data['material_kitting']['assessment'] != null){
              assessment = data['material_kitting']['device_info']['assessment'];
            }
            else{
              assessment = "";
            }
            $("#txt_ct_supplier").val('YEC');

          }
          else{
            $("#txt_po_number").val('');
            $("#txt_device_name_lbl").val('');
            $("#txt_device_code_lbl").val('');
            $("#txt_po_qty_lbl").val('');
            $("#txt_lot_qty").val('');

          }
        }
      });
    }

    $("#btnShowAddPilotRun").click(function(){
      if($("#txt_po_number").val() == "" || $("#txt_po_number").val() == null){
        toastr.warning('PO not found!');
      }
      else{

        $.ajax({
          url: "get_data_by_po_no",
          method: "get",
          data: {
              po_no: $("#txt_po_number").val(),
              device_name: $("#txt_device_name_lbl").val(),
              po_qty: $("#txt_po_qty_lbl").val(),
              validate: true,
          },
          dataType: "json",
          success: function(data){

            $("#modalPilotRunDetailsC").modal('show');

            // if( data['data'].length > 0 ){
            //   alert('meron')
              $("#txt_series_name").val($("#txt_device_name_lbl").val());
              $("#txt_po_num").val($("#txt_po_number").val());
              $("#txt_po_qty").val($("#txt_po_qty_lbl").val());

              GetMaterialKitting();
            // }else{
            //   alert('wala')
            // }

          }

        })

      }
    });

    $("#frmSaveCriticalFailures").submit(function(event){
      event.preventDefault();
      AddPilotRunC();
    });

    /* Packing Document */
    $('#txt_PackDocdrawing').change(function () {
      if($('#txt_PackDocdrawing').val() != 0) {
        var thiss = $(this);
        $('#list_txt_PackDocdrawing option').each(function(index) {
            var id = $(this).val();
            var name = $(this).text();
            var revision = $(this).attr('data-revision');

            if($(thiss).val()==id){
            $('#txt_PackDocdrawing_rev').val(revision);
            }
        });

      }else{
          $('#txt_PackDocdrawing_rev').val('N/A');
      }
    });

    $(".class_txt_PackDocdrawing").on("keyup", function(e){
      var parent        = $(this).closest(".row_container");
      var data = {
        "action"          : "get_PackDocdrawing",
        "device_name"     : $("#txt_device_name_lbl").val(),
        "str"             : $(this).val(),
      }
      data = $.param(data);
      $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_PackDocdrawing",
        success       : function(data){

          var list    = "";

           list+="<option value='N/A' data-revision='N/A' >N/A</option>";
          if ($.trim(data['doc'])){
            for(var ctr=0;ctr<data['doc'].length;ctr++){
              list+="<option value='"+data['doc'][ctr]['doc_no']+"' data-revision='"+data['doc'][ctr]['rev_no']+"'>"+data['doc'][ctr]['doc_no']+' '+data['doc'][ctr]['doc_title']+' '+data['doc'][ctr]['rev_no']+"</option>";

            }
          }

           console.log(data['doc'])

          $(parent).find(".class_dl_PackDocdrawing").html(list);
        }
      });
    });

    /* Redirect to RAPID ACDCS */
    $("#btnView_gotoRapid").click(function(){
      redirect_to_drawing( $('#txt_AKLdrawing').val(), 0 )
    });

    $("#btnView_AKLdrawing").click(function(){
      redirect_to_drawing( $('#txt_AKLdrawing').val(), 0 )
    });

    $("#btnView_GAGdrawing").click(function(){
      redirect_to_drawing( $('#txt_GAGdrawing').val(), 1 )
    });

    $("#btnView_PackDocdrawing").click(function(){
      redirect_to_drawing( $('#txt_PackDocdrawing').val(), 2 )
    });

    $("#btnView_PackSpecsdrawing").click(function(){
      redirect_to_drawing( $('#txt_PackSpecsdrawing').val(), 3 )
    });

    $("#btnView_OTUDdrawing").click(function(){
      redirect_to_drawing( $('#txt_OTUDdrawing').val(), 4 )
    });

    $("#btnView_WIDdrawing").click(function(){
      redirect_to_drawing( $('#txt_WIDdrawing').val(), 5 )
    });

    function redirect_to_drawing(txt_Adrawing, index) {
      if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' ){
        alert('No Document Required')
      }
      else{
        window.open("http://rapid/ACDCS/prdn_home_pats?doc_no="+txt_Adrawing)
        checked_draw_count[index] = 1
      }
    }

    let checked_draw_count
    function checked_draw_count_reset() {
      checked_draw_count = [0, 0, 0, 0, 0, 0]
    }


    /* Employee Scanning */
    $('.btnSearchProdn').click(function(){
      $('#txt_employee_id').val('');
      let dataCheckedBy = $(this).attr('data-checked-by'); // for dynamic checked_by input
      let dataCheckedByHidden = $(this).attr('data-checked-by-hidden'); // for dynamic checked_by input
      console.log('dataCheckedBy: ', dataCheckedBy);
      console.log('dataCheckedByHidden: ', dataCheckedByHidden);
      $('#modalSearchProdn').attr('modal-checked-by', dataCheckedBy);
      $('#modalSearchProdn').attr('modal-checked-by-hidden', dataCheckedByHidden);
    });

    /**
     * -JD
    */
    $(document).on('click','.btnScan',function(e){
      let btnScanId = $(this).attr('id');
      console.log('btnScanId: ',btnScanId);
      $('#txt_employee_number_scanner').val('');
      $('#mdl_employee_number_scanner').attr('data-formid',btnScanId).modal('show');

    });


    $(document).on('keypress',function(e){
        $('#txt_employee_number_scanner').focus();
      if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){

        if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) ){
          var formid = $("#mdl_employee_number_scanner").attr('data-formid');
          if ( ( formid ).indexOf('#') > -1){
            $( formid ).submit();
          }
          else{
            switch( formid ){
              case 'btnScanOperator':
                $.ajax({
                  url: "get_user_by_employee_no",
                  method: "get",
                  data: {
                      employee_no: $("#txt_employee_number_scanner").val()
                  },
                  dataType: "json",
                  beforeSend: function(){},
                  success: function(data){
                    console.log('data: ',data['data']);
                    if(data['data'] != null){
                        if(data['data']['position'] == 1){ // prodn supv
                          $('input[name="operator_name"]', $("#frmSaveCriticalFailures")).val(data['data']['name']);
                          $('input[name="operator_id"]', $("#frmSaveCriticalFailures")).val(data['data']['id']);
                        }
                        else{
                          $('input[name="operator_name"]', $("#frmSaveCriticalFailures")).val('');
                          $('input[name="operator_id"]', $("#frmSaveCriticalFailures")).val('');
                          toastr.warning('Prodn Supervisor not found!');
                        }
                    }
                    else{
                        $('input[name="operator_name"]', $("#frmSaveCriticalFailures")).val('');
                        $('input[name="operator_id"]', $("#frmSaveCriticalFailures")).val('');
                        toastr.error('User not found!');
                    }
                  },
                  error: function(data, xhr, status){
                      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                  }
                });
                break;
              case 'btnScanEngineer':
                $.ajax({
                  url: "get_user_by_employee_no",
                  method: "get",
                  data: {
                      employee_no: $("#txt_employee_number_scanner").val()
                  },
                  dataType: "json",
                  beforeSend: function(){},
                  success: function(data){
                    console.log('data: ',data['data']);
                    if(data['data'] != null){
                        if(data['data']['position'] == 9){
                          $('input[name="engineer_name"]', $("#frmSaveCriticalFailures")).val(data['data']['name']);
                          $('input[name="engineer_id"]', $("#frmSaveCriticalFailures")).val(data['data']['id']);
                        }
                        else{
                          $('input[name="engineer_name"]', $("#frmSaveCriticalFailures")).val('');
                          $('input[name="engineer_id"]', $("#frmSaveCriticalFailures")).val('');
                          toastr.warning('Engineer not found!');
                        }
                    }
                    else{
                        $('input[name="engineer_name"]', $("#frmSaveCriticalFailures")).val('');
                        $('input[name="engineer_id"]', $("#frmSaveCriticalFailures")).val('');
                        toastr.error('User not found!');
                    }
                  },
                  error: function(data, xhr, status){
                      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                  }
                });
                break;
              case 'btnScanQC':
                $.ajax({
                  url: "get_user_by_employee_no",
                  method: "get",
                  data: {
                      employee_no: $("#txt_employee_number_scanner").val()
                  },
                  dataType: "json",
                  beforeSend: function(){},
                  success: function(data){
                    console.log('data: ',data['data']);
                    if(data['data'] != null){
                        // if(data['data']['position'] == 2 || data['data']['position'] == 10){
                        if(data['data']['position'] == 2){ // QC suprv
                          $('input[name="qc_name"]', $("#frmSaveCriticalFailures")).val(data['data']['name']);
                          $('input[name="qc_id"]', $("#frmSaveCriticalFailures")).val(data['data']['id']);
                        }
                        else{
                          $('input[name="qc_name"]', $("#frmSaveCriticalFailures")).val('');
                          $('input[name="qc_id"]', $("#frmSaveCriticalFailures")).val('');
                          toastr.warning('QC not found!');
                        }
                    }
                    else{
                        $('input[name="qc_name"]', $("#frmSaveCriticalFailures")).val('');
                        $('input[name="qc_id"]', $("#frmSaveCriticalFailures")).val('');
                        toastr.error('User not found!');
                    }
                  },
                  error: function(data, xhr, status){
                      toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                  }
                });
                break;
              default:
                break;
            }
          }
          $('#mdl_employee_number_scanner').modal('hide');
        }
      }
    });

    $("#txt_employee_id").keydown(function (e) {
      let modalCheckedByValues = $('#modalSearchProdn').attr('modal-checked-by'); // for dynamic checked_by input
      let modalCheckedByHiddenValues = $('#modalSearchProdn').attr('modal-checked-by-hidden'); // for dynamic checked_by input

        if (e.keyCode == 13) {

          $.ajax({
            'data'      : {
              employee_id: $("#txt_employee_id").val(),
              position: 3,
              // user_level_id: 4,
            },
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'employee_id_checker',
            success     : function(JsonObject){

              if(JsonObject['result'] == 1)
                GetEmployeeDetails($('#txt_employee_id').val(), modalCheckedByValues, modalCheckedByHiddenValues);
              else if(JsonObject['result'] == 0)
                toastr.error('Scanned Employee ID is not Prodn Employee.');
              else
                toastr.error(JsonObject['error_msg']);
            },
            completed     : function(data){

            },
            error     : function(data){

            },
          });

        }
    });

    function GetEmployeeDetails(employee_id, modalCheckedByValues, modalCheckedByHiddenValues){
      $.ajax({
        url: "load_user_details",
        method: "get",
        data:
        {
          employee_id: employee_id,
        },
        dataType: "json",
        beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            let operator_names
            let userId = JsonObject['user_details'][0].id;
            let userName = JsonObject['user_details'][0].name;
              $('#'+modalCheckedByHiddenValues).val(userId.toString());
              $('#'+modalCheckedByValues).val(userName.toString());
          }
          else
          {
            toastr.error('Employee ID not Found!');
          }
        },
        error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

      });
    }

    $('input[type=radio][name=special_control_type]').change(function(){
      if (this.value == '6') {
          $('input[name=special_other_type]').attr("disabled",false);
      }else{
          $('input[name=special_other_type]').attr("disabled",true);
      }
    });

    $('input[type=radio][name=control_type]').change(function(){
      if (this.value == '2') {
          $('input[name=special_control_type]').attr("disabled",true);
          $('input[name=special_other_type]').attr("disabled",true);
          $('input[name=special_other_type]').val('');
      }else{
          $('input[name=special_control_type]').attr("disabled",false);
          $('input[name=special_other_type]').attr("disabled",false);
      }
    });

    $('input[type=radio][name=with_accessory_req]').change(function(){
      if (this.value == '2') {
          $('#txt_with_accessory_req_no').attr("readonly",true);
      }else{
          $('#txt_with_accessory_req_no').attr("readonly",false);
      }
    });

    $("#tblAssemblyAnalysis").on('click','.btnEditAssemblyAnalysis',function(e){
      e.preventDefault();
      pilotRunCId = $(this).attr('pilot-run-c-id');
      console.log('pilotRunCId ',pilotRunCId);

      let global_pilotrun_id = $('#global_pilotrun_id', $('#frm_pilotrun_Cdetails')).val();
      let global_pilotrun_status = $('#global_pilotrun_status', $('#frm_pilotrun_Cdetails')).val();
      console.log('btnEditAssemblyAnalysis global_pilotrun_id: ', global_pilotrun_id);
      console.log('btnEditAssemblyAnalysis global_pilotrun_status: ', global_pilotrun_status);

      if (global_pilotrun_status == 1){
        $("#btnScanEngineer").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }else if (global_pilotrun_status == 3){
        $("#btnScanOperator").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }else if (global_pilotrun_status == 5){
        $("#btnScanOperator").prop('disabled', true)
        $("#btnScanEngineer").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }else if (global_pilotrun_status == 6){
        $("#btnScanOperator").prop('disabled', true)
        $("#btnScanEngineer").prop('disabled', true)
      }

      $('#global_pilotrun_id', $('#frmSaveCriticalFailures')).val(global_pilotrun_id);
      GetPilotRun_c_details(pilotRunCId);
    });

    function GetPilotRun_c_details(pilotRunCId){
      $.ajax({
        url: "get_pilotrun_c_details",
        method: 'get',
        dataType: 'json',
        data: {
          pilot_run_c_id: pilotRunCId
        },
        beforeSend: function(){
        },
        success: function(data){
          $('#mdlSaveCriticalFailures').modal('show');

          if(data['pilot_run_c_details'] != null){
            $('input[name="pilot_run_c_id"]').val(data['pilot_run_c_details']['id']);
            $('select[name="txt_pot_fail"]').val(data['pilot_run_c_details']['prdn_potential_failure']).trigger('change');
            $('input[name="txt_aff_parts"]').val(data['pilot_run_c_details']['prdn_affected_parts']);
            $('textarea[name="txt_action_taken"]').val(data['pilot_run_c_details']['prdn_action_taken']);
            $('input[name="txt_orcto"]').val(data['pilot_run_c_details']['prdn_orcto']);
            $('input[name="txt_prod_due_date"]').val(data['pilot_run_c_details']['prdn_due_date']);
            $('input[name="txt_tools_jigs"]').val(data['pilot_run_c_details']['prdn_tools_jigs']);
            $('input[name="txt_docs"]').val(data['pilot_run_c_details']['prdn_docs']);
            $('input[name="txt_eng_due_date"]').val(data['pilot_run_c_details']['eng_due_date']);

            if(data['pilot_run_c_details']['prod_user_info'] != null){
              $('input[name="operator_name"]').val(data['pilot_run_c_details']['prod_user_info']['name']);
              $('input[name="operator_id"]').val(data['pilot_run_c_details']['prod_user_info']['id']);
            }

            if(data['pilot_run_c_details']['eng_user_info'] != null){
              $('input[name="engineer_name"]').val(data['pilot_run_c_details']['eng_user_info']['name']);
              $('input[name="engineer_id"]').val(data['pilot_run_c_details']['eng_user_info']['id']);
            }

            if(data['pilot_run_c_details']['qc_user_info'] != null){
              $('input[name="qc_name"]').val(data['pilot_run_c_details']['qc_user_info']['name']);
              $('input[name="qc_id"]').val(data['pilot_run_c_details']['qc_user_info']['id']);
            }

            $('input[name="txt_val_date"]').val(data['pilot_run_c_details']['qc_datetime'])
            $('textarea[name="txt_critical_highlights"]').val(data['pilot_run_c_details']['highlights'])

            $('input[name="txt_pr_control_no"]').val(data['pilot_run_c_details']['pr_ctl_no']);

            if(data['pilot_run_c_details']['chk_ctl_no'] == 1) {
              $('#pr_ctl_checkbox').prop('checked', true);
            }else {
              $('#pr_ctl_checkbox').prop('checked', false);
            }

          }
          else{
            // $("#id_btn_save_pilotC").hide();


          }

        }
      });
    }

    $("#tbl_pilot_runC").on('click','.btnOpenPilotRunDetails',function(e){
        global_pilotrun_id = $(this).attr('pilotrun-id');
        global_pilotrun_status = $(this).attr('pilotrun-status');
        pilotrun_status = $(this).attr('pilotrun-status');
        // alert(pilotrun_status)

        // if (pilotrun_status == 2 ||
        //     pilotrun_status == 3 ||
        //     pilotrun_status == 4 ||
        //     pilotrun_status == 5 ||
        //     pilotrun_status == 6 ||
        //     pilotrun_status == 7 ||
        //     pilotrun_status == 8 ||
        //     pilotrun_status == 9){
          $('#id_btn_save_pilotC').hide();
        // }else{
        //   $('#id_btn_save_pilotC').show();
        // }

        $('#global_pilotrun_id').val(global_pilotrun_id);
        $('#global_pilotrun_status').val(global_pilotrun_status);
        console.log('tbl_pilot_runC btnOpenPilotRunDetails id: ',global_pilotrun_id);
        GetProdPilotRunById(global_pilotrun_id);
    });


    function GetProdPilotRunById(global_pilotrun_id){
      $.ajax({
        url: "get_pilotrun_by_id",
        method: 'get',
        dataType: 'json',
        data: {
          pilotrun_id: global_pilotrun_id
        },
        beforeSend: function(){
        },
        success: function(data){

          if(data['pilotrun'] != null){
            $("#modalPilotRunDetailsC").modal('show');
            // $("#id_btn_save_pilotC").hide();

            $("#global_pilotrun_id").val(data['pilotrun']['id']);
            $("#txt_series_name").val($("#txt_device_name_lbl").val());
            $("#txt_po_num").val($("#txt_po_number").val());
            $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
            $("#txt_pair_no").val(data['pilotrun']['pair_no']);
            $("#txt_start_datetime").val(data['pilotrun']['created_at']);

            // $(':radio:not(:checked)').attr('disabled', true);
            if(data['pilotrun']['control_type'] == 1) {
              $('#rdo_special_control').prop('checked', true);
              $(".rdo_special_control").css("color", "blue");
            }else if(data['pilotrun']['control_type'] == 2) {
              $('#rdo_regular_control').prop('checked', true);
              $(".rdo_regular_control").css("color", "blue");
            }

            if(data['pilotrun']['special_control_type'] == 1) {
              $('#rdo_special_control_1').prop('checked', true);
              $(".rdo_special_control_1").css("color", "blue");
            }else if(data['pilotrun']['special_control_type'] == 2) {
              $('#rdo_special_control_2').prop('checked', true);
              $(".rdo_special_control_2").css("color", "blue");
            }else if(data['pilotrun']['special_control_type'] == 3) {
              $('#rdo_special_control_3').prop('checked', true);
              $(".rdo_special_control_3").css("color", "blue");
            }else if(data['pilotrun']['special_control_type'] == 4) {
              $('#rdo_special_control_4').prop('checked', true);
              $(".rdo_special_control_4").css("color", "blue");
            }else if(data['pilotrun']['special_control_type'] == 5) {
              $('#rdo_special_control_5').prop('checked', true);
              $(".rdo_special_control_5").css("color", "blue");
            }else if(data['pilotrun']['special_control_type'] == 6) {
              $('#rdo_special_control_6').prop('checked', true);
              $(".rdo_special_control_6").css("color", "blue");
            }

            var arr = data['multiple_special_other_type'];
            for(var i = 0 ; i < arr.length; i++){
              multiple_sot = arr[i];

              if(multiple_sot == 1) {
                $('#rdo_special_other_1').prop("checked", true);
                $(".rdo_special_other_1").css("color", "blue");
              }else
              if(multiple_sot == 2) {
                $('#rdo_special_other_2').prop("checked", true);
                $(".rdo_special_other_2").css("color", "blue");
              }else
              if(multiple_sot == 3) {
                $('#rdo_special_other_3').prop("checked", true);
                $(".rdo_special_other_3").css("color", "blue");
              }else
              if(multiple_sot == 4) {
                $('#rdo_special_other_4').prop("checked", true);
                $(".rdo_special_other_4").css("color", "blue");
              }else
              if(multiple_sot == 5) {
                $('#rdo_special_other_5').prop("checked", true);
                $(".rdo_special_other_5").css("color", "blue");
              }else
              if(multiple_sot == 6) {
                $('#rdo_special_other_6').prop("checked", true);
                $(".rdo_special_other_6").css("color", "blue");
              }else
              if(multiple_sot == 7) {
                $('#rdo_special_other_7').prop("checked", true);
                $(".rdo_special_other_7").css("color", "blue");
              }else
              if(multiple_sot == 8) {
                $('#rdo_special_other_8').prop("checked", true);
                $(".rdo_special_other_8").css("color", "blue");
              }else
              if(multiple_sot == 9) {
                $('#rdo_special_other_9').prop("checked", true);
                $(".rdo_special_other_9").css("color", "blue");
              }
            }

            $("#txt_AKLdrawing").attr('readonly', true)
            $("#txt_AKLdrawing_rev").attr('readonly', true)
            $("#txt_AKLdrawing_page").attr('readonly', true)

            $("#txt_AKLdrawing").val(data['pilotrun']['1_akl_drawing'])
            $("#txt_AKLdrawing_rev").val(data['pilotrun']['1_akl_drawing_rev'])
            $("#txt_AKLdrawing_page").val(data['pilotrun']['1_akl_drawing_page'])
            $("#txt_AKLdrawing_pn").val(data['pilotrun']['checked_by_a1']['name'])
            $("#txt_AKLdrawing_remarks").val(data['pilotrun']['1_akl_drawing_remarks'])

            $("#txt_GAGdrawing").attr('readonly', true)
            $("#txt_GAGdrawing_rev").attr('readonly', true)
            $("#txt_GAGdrawing_page").attr('readonly', true)

            $("#txt_GAGdrawing").val(data['pilotrun']['1_gag_drawing'])
            $("#txt_GAGdrawing_rev").val(data['pilotrun']['1_gag_drawing_rev'])
            $("#txt_GAGdrawing_page").val(data['pilotrun']['1_gag_drawing_page'])
            $("#txt_GAGdrawing_pn").val(data['pilotrun']['checked_by_a2']['name'])
            $("#txt_GAGdrawing_remarks").val(data['pilotrun']['1_gag_drawing_remarks'])

            $("#txt_PackDocdrawing").attr('readonly', true)
            $("#txt_PackDocdrawing_rev").attr('readonly', true)
            $("#txt_PackDocdrawing_page").attr('readonly', true)

            $("#txt_PackDocdrawing").val(data['pilotrun']['1_packdoc_drawing'])
            $("#txt_PackDocdrawing_rev").val(data['pilotrun']['1_packdoc_drawing_rev'])
            $("#txt_PackDocdrawing_page").val(data['pilotrun']['1_packdoc_drawing_page'])
            $("#txt_PackDocdrawing_pn").val(data['pilotrun']['checked_by_a3']['name'])
            $("#txt_PackDocdrawing_remarks").val(data['pilotrun']['1_packdoc_drawing_remarks'])

            $("#txt_PackSpecsdrawing").attr('readonly', true)
            $("#txt_PackSpecsdrawing_rev").attr('readonly', true)
            $("#txt_PackSpecsdrawing_page").attr('readonly', true)

            $("#txt_PackSpecsdrawing").val(data['pilotrun']['1_packspecs_drawing'])
            $("#txt_PackSpecsdrawing_rev").val(data['pilotrun']['1_packspecs_drawing_rev'])
            $("#txt_PackSpecsdrawing_page").val(data['pilotrun']['1_packspecs_drawing_page'])
            $("#txt_PackSpecsdrawing_pn").val(data['pilotrun']['checked_by_a4']['name'])
            $("#txt_PackSpecsdrawing_remarks").val(data['pilotrun']['1_packspecs_drawing_remarks'])

            $("#txt_OTUDdrawing").attr('readonly', true)
            $("#txt_OTUDdrawing_rev").attr('readonly', true)
            $("#txt_OTUDdrawing_page").attr('readonly', true)

            $("#txt_OTUDdrawing").val(data['pilotrun']['1_otud_drawing'])
            $("#txt_OTUDdrawing_rev").val(data['pilotrun']['1_otud_drawing_rev'])
            $("#txt_OTUDdrawing_page").val(data['pilotrun']['1_otud_drawing_page'])
            $("#txt_OTUDdrawing_pn").val(data['pilotrun']['checked_by_a5']['name'])
            $("#txt_OTUDdrawing_remarks").val(data['pilotrun']['1_otud_drawing_remarks'])

            $("#txt_WIDdrawing").attr('readonly', true)
            $("#txt_WIDdrawing_rev").attr('readonly', true)
            $("#txt_WIDdrawing_page").attr('readonly', true)

            $("#txt_WIDdrawing").val(data['pilotrun']['1_wi_drawing'])
            $("#txt_WIDdrawing_rev").val(data['pilotrun']['1_wi_drawing_rev'])
            $("#txt_WIDdrawing_page").val(data['pilotrun']['1_wi_drawing_page'])
            $("#txt_WIDdrawing_pn").val(data['pilotrun']['checked_by_a6']['name'])
            $("#txt_WIDdrawing_remarks").val(data['pilotrun']['1_wi_drawing_remarks'])

            if(data['pilotrun']['2_prov_parts_req'] == 1) {
              $('#rdo_provi_req_yes').prop('checked', true);
              $(".rdo_provi_req_yes").css("color", "blue");
            }else if(data['pilotrun']['2_prov_parts_req'] == 2) {
              $('#rdo_provi_req_no').prop('checked', true);
              $(".rdo_provi_req_no").css("color", "blue");
            }
            $("#txt_provi_req_pn").val(data['pilotrun']['checked_by_a7']['name'])
            $("#txt_provi_req_remarks").val(data['pilotrun']['2_prov_parts_req_remarks'])

            if(data['pilotrun']['2_assbly_parts_req'] == 1) {
              $('#rdo_assmbly_req_yes').prop('checked', true);
              $(".rdo_assmbly_req_yes").css("color", "blue");
            }else if(data['pilotrun']['2_assbly_parts_req'] == 2) {
              $('#rdo_assmbly_req_no').prop('checked', true);
              $(".rdo_assmbly_req_no").css("color", "blue");
            }
            $("#txt_assmbly_req_pn").val(data['pilotrun']['checked_by_a8']['name'])
            $("#txt_assmbly_req_remarks").val(data['pilotrun']['2_assbly_parts_remarks'])

            if(data['pilotrun']['2_withacc_parts_req'] == 1) {
              $('#rdo_with_accessory_req_yes').prop('checked', true);
              $(".rdo_with_accessory_req_yes").css("color", "blue");
            }else if(data['pilotrun']['2_withacc_parts_req'] == 2) {
              $('#rdo_with_accessory_req_no').prop('checked', true);
              $(".rdo_with_accessory_req_no").css("color", "blue");
              // $('#txt_with_accessory_req_no').attr('readonly', true);
            }
            // MIGZ 05-28-2025
           if(data['pilotrun']['2_parts_app_design_prodn'] == 1) {
              $('#parts_app_design_prodn_yes').prop('checked', true);
              $(".parts_app_design_prodn_yes").css("color", "blue");
            }else if(data['pilotrun']['2_parts_app_design_prodn'] == 2) {
              $('#parts_app_design_prodn_no').prop('checked', true);
              $(".parts_app_design_prodn_no").css("color", "blue");
            }
            console.log('2_parts_app_design_engg',data['pilotrun']['2_parts_app_design_engg']);
            if(data['pilotrun']['2_parts_app_design_engg'] == 1) {
              $('#parts_app_design_engg_yes').prop('checked', true);
              $(".parts_app_design_engg_yes").css("color", "blue");
            }else if(data['pilotrun']['2_parts_app_design_engg'] == 2) {
              $('#parts_app_design_engg_no').prop('checked', true);
              $(".parts_app_design_engg_no").css("color", "blue");
            }
            if(data['pilotrun']['2_parts_app_design_qc'] == 1) {
              $('#parts_app_design_qc_yes').prop('checked', true);
              $(".parts_app_design_qc_yes").css("color", "blue");
            }else if(data['pilotrun']['2_parts_app_design_qc'] == 2) {
              $('#parts_app_design_qc_no').prop('checked', true);
              $(".parts_app_design_qc_no").css("color", "blue");
            }
            if(data['pilotrun']['checked_by_prodn_2_4'] != null){
                $("#hidden_parts_app_design_prodn_pn").val(data['pilotrun']['2_parts_app_design_prodn_checkedby'])
                $("#txt_parts_app_design_prodn_pn").val(data['pilotrun']['checked_by_prodn_2_4']['name'] ?? '')
                $("#parts_app_design_prodn_remarks").val(data['pilotrun']['2_parts_app_design_prodn_remarks'])
            }
            if(data['pilotrun']['checked_by_engg_2_4'] != null){
                $("#hidden_parts_app_design_engg_pn").val(data['pilotrun']['2_parts_app_design_engg_checkedby'])
                $("#txt_parts_app_design_engg_pn").val(data['pilotrun']['checked_by_engg_2_4']['name'])
                $("#parts_app_design_engg_remarks").val(data['pilotrun']['2_parts_app_design_engg_remarks'])
            }
            if(data['pilotrun']['checked_by_qc_2_4'] != null){
                $("#hidden_parts_app_design_qc_pn").val(data['pilotrun']['2_parts_app_design_qc_checkedby'])
                $("#txt_parts_app_design_qc_pn").val(data['pilotrun']['checked_by_qc_2_4']['name'])
                $("#parts_app_design_qc_remarks").val(data['pilotrun']['2_parts_app_design_qc_remarks'])
            }
            
            $("#txt_with_accessory_req_no").val(data['pilotrun']['2_withacc_parts_req_no'])
            $("#txt_with_accessory_req_pn").val(data['pilotrun']['checked_by_a9']['name'])
            $("#txt_with_accessory_req_remarks").val(data['pilotrun']['2_withacc_parts_req_remarks'])

            $("#txt_assembly_type").val(data['pilotrun']['3_assblt_type']).trigger('change');
            $("#txt_assembly_type_pn").val(data['pilotrun']['checked_by_a10']['name'])
            $("#txt_assembly_type_remarks").val(data['pilotrun']['3_assblt_type_remarks'])

            $("#txt_parts_printing").val(data['pilotrun']['3_parts_printing'])
            $("#txt_printing_req").val(data['pilotrun']['3_parts_printing_no'])
            $("#txt_parts_printing_pn").val(data['pilotrun']['checked_by_a11']['name'])
            $("#txt_parts_printing_remarks").val(data['pilotrun']['3_parts_printing_remarks'])
            $("#txt_char_printed").val(data['pilotrun']['3_char_printed'])
            $("#txt_page_ref_AKLdrawing").val(data['pilotrun']['3_ref_doc_akl_drawing'])
            $("#txt_page_ref_GAGdrawing").val(data['pilotrun']['3_ref_doc_gag_drawing'])

            if(data['pilotrun']['3_gluing_req'] == 1) {
              $('#rdo_gluing_req_with').prop('checked', true);
              $(".rdo_gluing_req_with").css("color", "blue");
            }else if(data['pilotrun']['3_gluing_req'] == 2) {
              $('#rdo_gluing_req_without').prop('checked', true);
              $(".rdo_gluing_req_without").css("color", "blue");
            }
            $("#txt_gluing_req_pn").val(data['pilotrun']['checked_by_a12']['name'])
            $("#txt_gluing_req_remarks").val(data['pilotrun']['3_gluing_req_remarks'])

            if(data['pilotrun']['3_loctite_req'] == 1) {
              $('#rdo_loctite_req_with').prop('checked', true);
              $(".rdo_loctite_req_with").css("color", "blue");
            }else if(data['pilotrun']['3_loctite_req'] == 2) {
              $('#rdo_loctite_req_without').prop('checked', true);
              $(".rdo_loctite_req_without").css("color", "blue");
            }
            $("#txt_loctite_req_pn").val(data['pilotrun']['checked_by_a13']['name'])
            $("#txt_loctite_req_remarks").val(data['pilotrun']['3_loctite_req_remarks'])

            if(data['pilotrun']['3_hvt_req'] == 1) {
              $('#rdo_hvt_req_with').prop('checked', true);
              $(".rdo_hvt_req_with").css("color", "blue");
            }else if(data['pilotrun']['3_hvt_req'] == 2) {
              $('#rdo_hvt_req_without').prop('checked', true);
              $(".rdo_hvt_req_without").css("color", "blue");
            }
            $("#txt_hvt_req_pn").val(data['pilotrun']['checked_by_a14']['name'])
            $("#txt_hvt_req_remarks").val(data['pilotrun']['3_hvt_req_remarks'])

            if(data['pilotrun']['3_etest_req'] == 1) {
              $('#rdo_etest_req_with').prop('checked', true);
              $(".rdo_etest_req_with").css("color", "blue");
            }else if(data['pilotrun']['3_etest_req'] == 2) {
              $('#rdo_etest_req_without').prop('checked', true);
              $(".rdo_etest_req_without").css("color", "blue");
            }
            $("#txt_etest_req_pn").val(data['pilotrun']['checked_by_a15']['name'])
            $("#txt_etest_req_remarks").val(data['pilotrun']['3_etest_req_remarks'])

            if(data['pilotrun']['3_gauge_req'] == 1) {
              $('#rdo_gauge_req_with').prop('checked', true);
              $(".rdo_gauge_req_with").css("color", "blue");
            }else if(data['pilotrun']['3_gauge_req'] == 2) {
              $('#rdo_gauge_req_without').prop('checked', true);
              $(".rdo_gauge_req_without").css("color", "blue");
            }
            $("#txt_gauge_control_no").val(data['pilotrun']['3_gauge_ctl_no'])
            $("#txt_gauge_req_pn").val(data['pilotrun']['checked_by_a16']['name'])
            $("#txt_gauge_req_remarks").val(data['pilotrun']['3_gauge_req_remarks'])

            if(data['pilotrun']['3_need_planarity'] == 1) {
              $('#rdo_need_planarity_yes').prop('checked', true);
              $(".rdo_need_planarity_yes").css("color", "blue");
            }else if(data['pilotrun']['3_need_planarity'] == 2) {
              $('#rdo_need_planarity_no').prop('checked', true);
              $(".rdo_need_planarity_no").css("color", "blue");
            }

            $('input[name="txt_need_planarity_pn"]').val(data['checked_by_a17'])

            $("#txt_need_planarity_remarks").val(data['pilotrun']['3_need_planarity_remarks'])

            // alert(data['pilotrun']['status'])

            //01122023
            // if (data['pilotrun']['status'] == 2 || data['pilotrun']['status'] == 4 || data['pilotrun']['status'] == 5 || data['pilotrun']['status'] == 7 || data['pilotrun']['status'] == 8){
            //   $("#btnSaveCriticalFailures").hide();
            // }

          }
          else{
            // $("#txt_pair_no").val('');
            // $("#txt_start_datetime").val('');

            // $('b').removeAttr('style');
            // $('i').removeAttr('style');
            // $('i').removeAttr('style');
            // $(':radio(:checked)').attr('disabled', false);

            // $("input:radio[name=control_type]:checked")[0].checked = false;
            // $("input:radio[name=special_control_type]:checked")[0].checked = false;
            // $("input:radio[name=special_other_type]:checked")[0].checked = false;

            // // $("#txt_AKLdrawing").val('');
            // // $("#txt_AKLdrawing_rev").val('');
            // $("#txt_AKLdrawing_page").val('');
            // $("#hidden_AKLdrawing_pn").val('');
            // $("#txt_AKLdrawing_pn").val('');
            // $("#txt_AKLdrawing_remarks").val('');
            // $("#txt_AKLdrawing_page").attr('readonly', false)
            // $("#txt_AKLdrawing_remarks").attr('readonly', false)

            // // $("#txt_GAGdrawing").val('');
            // // $("#txt_GAGdrawing_rev").val('');
            // $("#txt_GAGdrawing_page").val('');
            // $("#hidden_GAGdrawing_pn").val('');
            // $("#txt_GAGdrawing_pn").val('');
            // $("#txt_GAGdrawing_remarks").val('');
            // $("#txt_GAGdrawing_page").attr('readonly', false)
            // $("#txt_GAGdrawing_remarks").attr('readonly', false)

            // $("#txt_PackDocdrawing").val('');
            // $("#txt_PackDocdrawing_rev").val('');
            // $("#txt_PackDocdrawing_page").val('');
            // $("#hidden_PackDocdrawing_pn").val('');
            // $("#txt_PackDocdrawing_pn").val('');
            // $("#txt_PackDocdrawing_remarks").val('');
            // $("#txt_PackDocdrawing").attr('readonly', false)
            // $("#txt_PackDocdrawing_rev").attr('readonly', false)
            // $("#txt_PackDocdrawing_page").attr('readonly', false)
            // $("#txt_PackDocdrawing_remarks").attr('readonly', false)

            // $("#txt_PackSpecsdrawing").val('');
            // $("#txt_PackSpecsdrawing_rev").val('');
            // $("#txt_PackSpecsdrawing_page").val('');
            // $("#hidden_PackSpecsdrawing_pn").val('');
            // $("#txt_PackSpecsdrawing_pn").val('');
            // $("#txt_PackSpecsdrawing_remarks").val('');
            // $("#txt_PackSpecsdrawing").attr('readonly', false)
            // $("#txt_PackSpecsdrawing_rev").attr('readonly', false)
            // $("#txt_PackSpecsdrawing_page").attr('readonly', false)
            // $("#txt_PackDocdrawing_remarks").attr('readonly', false)

            // $("#txt_OTUDdrawing").val('');
            // $("#txt_OTUDdrawing_rev").val('');
            // $("#txt_OTUDdrawing_page").val('');
            // $("#hidden_OTUDdrawing_pn").val('');
            // $("#txt_OTUDdrawing_pn").val('');
            // $("#txt_OTUDdrawing_remarks").val('');
            // $("#txt_OTUDdrawing").attr('readonly', false)
            // $("#txt_OTUDdrawing_rev").attr('readonly', false)
            // $("#txt_OTUDdrawing_page").attr('readonly', false)
            // $("#txt_OTUDdrawing_remarks").attr('readonly', false)

            // $("#txt_WIDdrawing").val('');
            // $("#txt_WIDdrawing_rev").val('');
            // $("#txt_WIDdrawing_page").val('');
            // $("#hidden_WIDdrawing_pn").val('');
            // $("#txt_WIDdrawing_pn").val('');
            // $("#txt_WIDdrawing_remarks").val('');
            // $("#txt_WIDdrawing").attr('readonly', false)
            // $("#txt_WIDdrawing_rev").attr('readonly', false)
            // $("#txt_WIDdrawing_page").attr('readonly', false)
            // $("#txt_WIDdrawing_remarks").attr('readonly', false)


          }

          GetPilotRun_b_details(global_pilotrun_id);
          dt_pilot_run_c.draw(); // -JD
        }
      });
    }

    $('input[type=radio][name=feedback_report]').change(function(){
      if (this.value == '1') {
          $('#txt_ptis').attr("disabled",false);
          $('#txt_ng_report').attr("disabled",true);
          $('#txt_sar').attr("disabled",true);
          $('#txt_others').attr("disabled",true);
      }else if (this.value == '2') {
          $('#txt_ptis').attr("disabled",true);
          $('#txt_ng_report').attr("disabled",false);
          $('#txt_sar').attr("disabled",true);
          $('#txt_others').attr("disabled",true);
      }else if (this.value == '3') {
          $('#txt_ptis').attr("disabled",true);
          $('#txt_ng_report').attr("disabled",true);
          $('#txt_sar').attr("disabled",false);
          $('#txt_others').attr("disabled",true);
      }else if (this.value == '4') {
          $('#txt_ptis').attr("disabled",true);
          $('#txt_ng_report').attr("disabled",true);
          $('#txt_sar').attr("disabled",true);
          $('#txt_others').attr("disabled",false);
      }
    });

    function GetPilotRun_b_details(global_pilotrun_id){
      $.ajax({
        url: "get_pilotrun_b_details",
        method: 'get',
        dataType: 'json',
        data: {
          pilot_run_b_id: global_pilotrun_id
        },
        beforeSend: function(){
        },
        success: function(data){

          if(data['pilotrun_b'] != null){
            // $("#id_btn_save_pilotC").hide();

            if(data['pilotrun_b']['term_length'] == 1) {
              $('#rdo_term_length_ok').prop('checked', true);
              $(".rdo_term_length_ok").css("color", "blue");
            }else if(data['pilotrun_b']['term_length'] == 2) {
              $('#rdo_term_length_ng').prop('checked', true);
              $(".rdo_term_length_ng").css("color", "blue");
            }else if(data['pilotrun_b']['term_length'] == 3) {
              $('#rdo_term_length_na').prop('checked', true);
              $(".rdo_term_length_na").css("color", "blue");
            }
            if(data['pilotrun_b']['measurement_result'] == 1) {
              $('#rdo_measure_result_ok').prop('checked', true);
              $(".rdo_measure_result_ok").css("color", "blue");
            }else if(data['pilotrun_b']['measurement_result'] == 2) {
              $('#rdo_measure_result_ng').prop('checked', true);
              $(".rdo_measure_result_ng").css("color", "blue");
            }else if(data['pilotrun_b']['measurement_result'] == 3) {
              $('#rdo_measure_result_na').prop('checked', true);
              $(".rdo_measure_result_na").css("color", "blue");
            }
            if(data['pilotrun_b']['dimension'] == 1) {
              $('#rdo_dimension_type_1').prop('checked', true);
              $(".rdo_dimension_type_1").css("color", "blue");
            }else if(data['pilotrun_b']['dimension'] == 2) {
              $('#rdo_dimension_type_2').prop('checked', true);
              $(".rdo_dimension_type_2").css("color", "blue");
            }else if(data['pilotrun_b']['dimension'] == 3) {
              $('#rdo_dimension_type_3').prop('checked', true);
              $(".rdo_dimension_type_3").css("color", "blue");
              $("#txt_special_req").val(data['pilotrun_b']['dimension_with_specialReq'])
            }
            if(data['pilotrun_b']['vir'] == 1) {
              $('#rdo_vir_ok').prop('checked', true);
              $(".rdo_vir_ok").css("color", "blue");
            }else if(data['pilotrun_b']['vir'] == 2) {
              $('#rdo_vir_ng').prop('checked', true);
              $(".rdo_vir_ng").css("color", "blue");
            }
            if(data['pilotrun_b']['feedback_report'] == 1) {
              $('#rdo_ptis').prop('checked', true);
              $(".rdo_ptis").css("color", "blue");
              $("#txt_ptis").val(data['pilotrun_b']['ptis_ctlno'])
            }else if(data['pilotrun_b']['feedback_report'] == 2) {
              $('#rdo_ng_report').prop('checked', true);
              $(".rdo_ng_report").css("color", "blue");
              $("#txt_ng_report").val(data['pilotrun_b']['ng_ctlno'])
            }else if(data['pilotrun_b']['feedback_report'] == 3) {
              $('#rdo_sar').prop('checked', true);
              $(".rdo_sar").css("color", "blue");
              $("#txt_sar").val(data['pilotrun_b']['sar_ctlno'])
            }else if(data['pilotrun_b']['feedback_report'] == 4) {
              $('#rdo_others').prop('checked', true);
              $(".rdo_others").css("color", "blue");
              $("#txt_others").val(data['pilotrun_b']['other_ctlno'])
            }

            $("#txt_term_length_pn").val(data['pilotrun_b']['term_length_checkedby']['name'])
            $("#txt_term_length_date").val(data['pilotrun_b']['term_length_datetime'])
            $("#txt_measure_result_pn").val(data['pilotrun_b']['measurement_result_checkedby']['name'])
            $("#txt_measure_result_date").val(data['pilotrun_b']['measurement_result_datetime'])
            $("#txt_vir_remarks").val(data['pilotrun_b']['vir_remarks'])
            $("#txt_highlights").val(data['pilotrun_b']['highlights'])

            // $("#global_pilotrun_id").val(data['pilotrun_b']['id']);
            // $("#global_pilotrun_id").val(data['pilotrun_b']['id']);
            // $("#txt_start_datetime").val(data['pilotrun_b']['created_at']);

          }
          else{
            $("#id_btn_save_pilotC").hide();
          }

        }
      });
    }

    $("#modalPilotRunDetailsC").on('hidden.bs.modal', function () {
        $("#frm_pilotrun_Cdetails")[0].reset();

        $('b').removeAttr('style');
        $('i').removeAttr('style');
        $('i').removeAttr('style');
        $(':radio(:checked)').attr('disabled', false);

        $("#txt_PackDocdrawing").attr('readonly', false)
        $("#txt_PackDocdrawing_rev").attr('readonly', false)
        $("#txt_PackDocdrawing_page").attr('readonly', false)

        $("#txt_PackSpecsdrawing").attr('readonly', false)
        $("#txt_PackSpecsdrawing_rev").attr('readonly', false)
        $("#txt_PackSpecsdrawing_page").attr('readonly', false)

        $("#txt_OTUDdrawing").attr('readonly', false)
        $("#txt_OTUDdrawing_rev").attr('readonly', false)
        $("#txt_OTUDdrawing_page").attr('readonly', false)

        $("#txt_WIDdrawing").attr('readonly', false)
        $("#txt_WIDdrawing_rev").attr('readonly', false)
        $("#txt_WIDdrawing_page").attr('readonly', false)
    });

    $("#mdlSaveCriticalFailures").on('hidden.bs.modal', function () {
      $("#frmSaveCriticalFailures")[0].reset();
      console.log('mdlSaveCriticalFailures is closed');
    });

    $("#btnShowAddCriticalFailure").click(function(e){
      e.preventDefault();
      let global_pilotrun_id = $('#global_pilotrun_id', $('#frm_pilotrun_Cdetails')).val();
      let global_pilotrun_status = $('#global_pilotrun_status', $('#frm_pilotrun_Cdetails')).val();
      console.log('btnShowAddCriticalFailure global_pilotrun_id: ', global_pilotrun_id);
      console.log('btnShowAddCriticalFailure global_pilotrun_status: ', global_pilotrun_status);

      if (global_pilotrun_status == 1){
        $("#btnScanEngineer").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }else if (global_pilotrun_status == 3){
        $("#btnScanOperator").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }else if (global_pilotrun_status == 5){
        $("#btnScanOperator").prop('disabled', true)
        $("#btnScanEngineer").prop('disabled', true)
        $("#btnScanQC").prop('disabled', true)
      }

      $('#global_pilotrun_id', $('#frmSaveCriticalFailures')).val(global_pilotrun_id);
      $('#global_pilotrun_po', $('#frmSaveCriticalFailures')).val($("#txt_po_number").val());
      $('#mdlSaveCriticalFailures').modal('show');
    });

    var chkbox = $('#pr_ctl_checkbox');
    $("#pr_ctl_checkbox").change(function() {
      if ($(this).prop('checked')){
        $("#txt_pr_control_no").attr('readonly', false)
        $("#txt_pot_fail").attr("disabled", true);
        $("#txt_aff_parts").attr('readonly', true)
        $("#txt_action_taken").attr('readonly', true)
        $("#txt_orcto").attr('readonly', true)
        $("#txt_prod_due_date").attr('readonly', true)
        $("#txt_tools_jigs").attr('readonly', true)
        $("#txt_docs").attr('readonly', true)
        $("#txt_eng_due_date").attr('readonly', true)

      }else{
        $("#txt_pr_control_no").attr('readonly', true)
        $("#txt_pot_fail").attr("disabled", false);
        $("#txt_aff_parts").attr('readonly', false)
        $("#txt_action_taken").attr('readonly', false)
        $("#txt_orcto").attr('readonly', false)
        $("#txt_prod_due_date").attr('readonly', false)
        $("#txt_tools_jigs").attr('readonly', false)
        $("#txt_docs").attr('readonly', false)
        $("#txt_eng_due_date").attr('readonly', false)
      }
    });

  });

/* Search PO */
  $(".btnSearchPoNo").click(function(){
    $("#txtSearchPoNum").val('');
    $('#modalScanPONum').attr('data-formid', 'search_po_number').modal('show');
  });

  $(document).on('keypress',function(e){
    if( ($("#modalScanPONum").data('bs.modal') || {})._isShown ){
      $('#txtSearchPoNum').focus();
      if( e.keyCode == 13 && $('#txtSearchPoNum').val() !='' && ($('#txtSearchPoNum').val().length >= 4) ){
        $('#modalScanPONum').modal('hide');
        var formid = $("#modalScanPONum").attr('data-formid');

        if ( ( formid ).indexOf('#') > -1){
          $( formid ).submit();
        }
        else{
          switch( formid ){
            case 'search_po_number':
              currentPoNo = $("#txtSearchPoNum").val();
              $("#txt_po_number").val(currentPoNo);
              dt_pilot_run.draw();
              $('#txt_po_number').val('');
              $('#txt_device_name_lbl').val('');
              $('#txt_device_code_lbl').val('');
              $('#txt_po_qty_lbl').val('');
              $('#txt_device_name').val('');
              $(this).val('');
              $(this).focus();
            break;
            default:
            break;
          }
        }
      }
    }
  });

  $(document).on('keypress',function(e){
    if( e.keyCode == 13 && $('#txt_po_number').val() !='' && $('#txt_po_number').val().length >= 4){

      currentPoNo = $("#txt_po_number").val();
      dt_pilot_run.draw();
      $('#txt_po_number').val('');
      $('#txt_device_name_lbl').val('');
      $('#txt_device_code_lbl').val('');
      $('#txt_po_qty_lbl').val('');
      $('#txt_device_name').val('');
      $(this).val('');
      $(this).focus();
    }
  });







  </script>
  @endsection
@endauth
