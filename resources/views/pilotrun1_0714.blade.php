@php $layout = 'layouts.super_user_layout'; @endphp
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'Pilot Run1')

  @section('content_page')
  <style type="text/css">
    .modal-xl-custom{
      width: 95%!important;
      min-width: 95%!important;
    }
    .modal-footer {
      justify-content: space-between;
    }  
</style>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>A - Pilot Run</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Pilot Run</li>
            </ol>
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
                      <input type="text" class="form-control" id="txt_po_number_lbl">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <label>Series Name</label>
                    <input type="text" class="form-control" id="txt_device_name_lbl" readonly>
                  </div>
                  <div class="col-sm-2">
                    <label>Series Code</label>
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
                <h3 class="card-title">A - Pilot Run Summary (In-Charge: Production)</h3>
                <button class="btn btn-primary btn-sm" style="float: right;" id="btnShowAddPilotRun"><i class="fa fa-plus"></i> Add Pilot Run</button>
              </div>

              <div class="card-body">
                <div class="row">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tbl_pilot_run" style="min-width: 900px;">
                      <thead>
                        <tr class="bg-light">
                        <th>Action</th>
                        <th>Status</th>
                        <th>Lot #</th>
                        <th>Remarks</th>
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

    <div class="modal fade" id="modalPilotRunDetailsA" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> A - Pilot Run Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>

          <form id="frm_pilotrun_Adetails" method="post">
          @csrf
            <div class="modal-body">
              
              <center>
                <h3>PILOT RUN EVALUATION REPORT</h3>
              </center>
              <!-- <button type="button" id="btn_edit_pilotrun_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button> -->

              <input type="hidden" name="employee_id" id="frm_edit_pilotrun_details_employee_id">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_series_name" name="series_name" style="color: blue; font-weight: bold;" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Num</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_num" name="po_no" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_po_qty" name="po_qty" readonly>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Pair No/s.</span>
                      </div>
                      <input type="Number" class="form-control form-control-sm" id="txt_pair_no" name="pair_no">
                    </div>
                    <div class="input-group input-group-sm mb-2">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Start Date/Time</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="txt_start_datetime" name="txt_start_datetime" readonly="true" placeholder="Auto generated">
                    </div>                    
                  </div>

                  <div class="col-sm-8">
                    TYPE OF CONTROL / QUALIFICATION / ACTIVITY TO PERFORM
                    
                    <div class="row">
                      <div class="col-sm-6">
                        <input type="radio" id="rdo_special_control" name="control_type" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;<b>Special Control (Conduct Pilot Run) - check items A to E)</b>
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_1" name="special_control_type" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;New Series (with MD)
                          </div>
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_2" name="special_control_type" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Long period of no production
                          </div>  
                        </div> 
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_3" name="special_control_type" value="3"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;New Device (With MD)
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_4" name="special_control_type" value="4"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Manual Pulling
                          </div> 
                        </div> 
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_5" name="special_control_type" value="5"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;New Product
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_control_6" name="special_control_type" value="6"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Others
                          </div> 
                        </div> 
                      </div>  

                      <div class="col-sm-6">
                        <b>Others</b>
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_1" name="special_other_type" value="1"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Toshiba/Kioxia Customer
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_2" name="special_other_type" value="2"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;With Customer Claim
                          </div> 
                        </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_3" name="special_other_type" value="3"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Micron Customer
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_4" name="special_other_type" value="4"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;With Lot-out
                          </div> 
                        </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_5" name="special_other_type" value="5"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;YEU Customer
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_6" name="special_other_type" value="6"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;Contribution of Yield off-target 
                          </div> 
                        </div>
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_7" name="special_other_type" value="7"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;AYE Customer
                          </div> 
                          <div class="col-sm-6">
                            <input type="radio" id="rdo_special_other_8" name="special_other_type" value="8"> 
                              &nbsp;&nbsp;&nbsp;&nbsp;With UD or Special Instructions
                          </div> 
                        </div>
                      </div>  
                    </div>  

                    <div class="row">
                      <div class="col-sm-8">
                        <input type="radio" id="rdo_regular_control" name="control_type" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;<b>Regular Control (Conduct Product qualification) - check items A, B, & E) <br>
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(All Series other than specified above)</b>
                      </div>  
                    </div>      
                  </div>

                </div>

                <div class="col-sm-9">
                  <span class="badge badge-warning">
                      SAMPLE SIZE / DEFINITION: 
                  </span>
                  <br>
                      SPECIAL CONTROL: 5 PCS. / Shall wait for the qualification result prior mass production <br>
                      REGULAR CONTROL: 1 PC. / Can proceed to mass production even without waiting the qualification result
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
                          <input type="text" class="form-control" id="txt_AKLdrawing_pn2" name="txt_AKLdrawing_pn2" placeholder="CHECKED BY" readonly>
                          <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-sm btnSearchProdn" id="btnSearchProdn" data-checked-by="txt_AKLdrawing_pn2" data-checked-by-hidden="hidden_AKLdrawing_pn" data-toggle="modal" data-target="#modalSearchProdn" title="Scan Employee ID"><i class="fa fa-barcode"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_AKLdrawing_pn2" data-remove-checked-by-hidden="hidden_AKLdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                            <!-- Newly added -->
                            <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_GAGdrawing_pn" data-remove-checked-by-hidden="hidden_GAGdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                          <!-- Newly added -->
                          <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_PackDocdrawing_pn" data-remove-checked-by-hidden="hidden_PackDocdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                            <!-- Newly added -->
                            <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_PackSpecsdrawing_pn" data-remove-checked-by-hidden="hidden_PackSpecsdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                            <!-- Newly added -->
                            <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_OTUDdrawing_pn" data-remove-checked-by-hidden="hidden_OTUDdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                            <!-- Newly added -->
                            <button type="button" class="btn btn-danger btn-sm btnPopLastProdn" id="btnPopLastProdn" data-remove-checked-by="txt_WIDdrawing_pn" data-remove-checked-by-hidden="hidden_WIDdrawing_pn" title="Remove Last Operator"><i class="fa fa-retweet"></i></button>
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
                        <input type="radio" id="rdo_provi_req_yes" name="rdo_provi_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;YES
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_provi_req_no" name="rdo_provi_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;NO
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
                        <input type="radio" id="rdo_assmbly_req_yes" name="rdo_assmbly_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;YES
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_assmbly_req_no" name="rdo_assmbly_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;NO
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
                        <input type="radio" id="rdo_with_accessory_req_yes" name="rdo_with_accessory_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;YES
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_with_accessory_req_no" name="rdo_with_accessory_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;NO
                      </div>
                      <div class="col-sm-2">
                        <div class="input-group input-group-sm mb-2">
                          <input type="text" class="form-control" id="txt_with_accessory_req_no" name="txt_with_accessory_req_no" placeholder="NO. OF ACCESSORY">
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
                          <input type="text" class="form-control" id="txt_printing_req" name="txt_printing_req">
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
                          <input type="text" class="form-control" id="txt_assembly_type_remarks" name="txt_assembly_type_remarks" placeholder="REMARKS">
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
                        <input type="radio" id="rdo_gluing_req_with" name="rdo_gluing_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_gluing_req_without" name="rdo_gluing_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                        <input type="radio" id="rdo_loctite_req_with" name="rdo_loctite_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_loctite_req_without" name="rdo_loctite_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                        <input type="radio" id="rdo_hvt_req_with" name="rdo_hvt_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_hvt_req_without" name="rdo_hvt_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                        <input type="radio" id="rdo_etest_req_with" name="rdo_etest_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_etest_req_without" name="rdo_etest_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                        <input type="radio" id="rdo_gauge_req_with" name="rdo_gauge_req" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_gauge_req_without" name="rdo_gauge_req" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                        <input type="radio" id="rdo_need_planarity_yes" name="rdo_need_planarity" value="1"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITH
                      </div>  
                      <div class="col-sm-1">
                        <input type="radio" id="rdo_need_planarity_no" name="rdo_need_planarity" value="2"> 
                          &nbsp;&nbsp;&nbsp;&nbsp;WITHOUT
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
                <button type="submit" id="id_btn_save_pilotA" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
                <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              </div>
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
            Please scan your Employee ID.
            <br>
            <br>
            <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_id" name="txt_employee_id" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>


  </div>


  @endsection

  @section('js_content')
  <script type="text/javascript">
    let currentPoNo = "";
    let arrayProdnEmp = [];
    let arrayProdnEmpName = [];


    let dt_pilot_run;

  $(document).ready(function () {

    $('#txt_po_number_lbl').keypress(function(e){
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

    dt_pilot_run = $('#tbl_pilot_run').DataTable({
      "processing" : true,
        "serverSide" : true,
        "ajax" : {
          url: "get_pilotrun_by_po",
          data: function (param){
              param.po_number = currentPoNo;
          }
        },
        bAutoWidth: false,

        "columns":[
          { "data" : "raw_action", orderable:false, searchable:false },
          { "data" : "raw_status", orderable:false, searchable:false },
          { "data" : "lot_no" },
          { "data" : "remarks" },
        ],
        "columnDefs": [
          {
            "targets": [5, 6],
            "data": null,
            "defaultContent": "--"
          },
        ],
        order: [[2, "desc"]],
        "rowCallback": function(row,data,index ){
          currentPoNo = data['po_no'];
          $('#txt_po_number_lbl').val( data['po_no'] );
          $('#txt_device_name_lbl').val( data['wbs_kitting']['device_name'] );
          $('#txt_po_qty_lbl').val( data['wbs_kitting']['po_qty'] );
        },
        "drawCallback": function(row,data,index ){
          // GetMaterialKittingListByPoNo($(".selAccessoryName"), $('#txt_po_number_lbl').val());

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
            $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
            $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
            $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
            $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);

            if (data['orig_a_drawing'] ==''){
              $("#txt_AKLdrawing").val('N/A');
              $("#txt_AKLdrawing_rev").val('N/A');
              $('.btnView_Adrawing').prop('disabled', true);
            }else{
              $("#txt_AKLdrawing").val(data['orig_a_drawing'][0]['doc_no']);
              $("#txt_AKLdrawing_rev").val(data['orig_a_drawing'][0]['rev_no']);
              $("#txt_AKLdrawing_page").val(data['orig_a_drawing'][0]['page_no']);
            }
            
            $("#txt_GAGdrawing").val(data['g_drawing'][0]['doc_no']);
            $("#txt_GAGdrawing_rev").val(data['g_drawing'][0]['rev_no']);
            $("#txt_GAGdrawing_page").val(data['g_drawing'][0]['page_no']);
            

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
            $("#txt_po_number_lbl").val('');
            $("#txt_device_name_lbl").val('');
            $("#txt_device_code_lbl").val('');
            $("#txt_po_qty_lbl").val(''); 
            $("#txt_lot_qty").val('');

          }
        }
      });
    }

    $("#btnShowAddPilotRun").click(function(){
      if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
        toastr.warning('PO not found!');
      }
      else{

        $.ajax({
          url: "get_first_lot_data_by_po_no",
          method: "get",
          data: {
              po_no: $("#txt_po_number_lbl").val(),
              device_name: $("#txt_device_name_lbl").val(),
              po_qty: $("#txt_po_qty_lbl").val(),
              validate: true,
          },
          dataType: "json",
          success: function(data){

            $("#modalPilotRunDetailsA").modal('show');

            $("#txt_series_name").val($("#txt_device_name_lbl").val());
            $("#txt_po_num").val($("#txt_po_number_lbl").val());
            $("#txt_po_qty").val($("#txt_po_qty_lbl").val());

          }

        })

      }
    });

    $("#frm_pilotrun_Adetails").submit(function(event){
      event.preventDefault();
      AddPilotRunA();
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
    $("#btnView_AKLdrawing").click(function(){
      redirect_to_drawing( $('#txt_AKLdrawing').val(), 0 )
    });

    $("#btnView_GAGdrawing").click(function(){
      redirect_to_drawing( $('#txt_GAGdrawing').val(), 1 )
    });

    $("#btnView_PackDocdrawing").click(function(){
      redirect_to_drawing( $('#txt_PackDocdrawing').val(), 2 )
    });

    function redirect_to_drawing(txt_Adrawing, index) {
      if ( txt_Adrawing == 'N/A' || txt_Adrawing == '' )
        alert('No Document Required')
      else{
        window.open("http://rapid/ACDCS/prdn_home_pats?doc_no="+txt_Adrawing)
        checked_draw_count[index] = 1
      }
    }

    let checked_draw_count
    function checked_draw_count_reset() {
      // checked_draw_count = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
      checked_draw_count = [0, 0, 0]
    }


    /* Employee Scanning */
    // $('#btnSearchProdn').click(function(){ // 07-14-2022
    $('.btnSearchProdn').click(function(){
      $('#txt_employee_id').val('');
      let dataCheckedBy = $(this).attr('data-checked-by'); // for dynamic checked_by input
      let dataCheckedByHidden = $(this).attr('data-checked-by-hidden'); // for dynamic checked_by input
      console.log('dataCheckedBy: ', dataCheckedBy);
      console.log('dataCheckedByHidden: ', dataCheckedByHidden);
      $('#modalSearchProdn').attr('modal-checked-by', dataCheckedBy);
      $('#modalSearchProdn').attr('modal-checked-by-hidden', dataCheckedByHidden);
    });

    $(document).on('keypress',function(e){
      if( ($("#modalSearchProdn").data('bs.modal') || {})._isShown ){
        $('#txt_employee_id').focus();
        let modalCheckedByValues = $('#modalSearchProdn').attr('modal-checked-by'); // for dynamic checked_by input
        let modalCheckedByHiddenValues = $('#modalSearchProdn').attr('modal-checked-by-hidden'); // for dynamic checked_by input
        if( e.keyCode == 13 && $('#txt_employee_id').val() !='' && ($('#txt_employee_id').val().length >= 4) ){

            $('#modalSearchProdn').modal('hide');

            $.ajax({
              url: "employee_id_checker",
              method: "get",
              data:
              {
                employee_id: $('#txt_employee_id').val(),
                // position: 1, // Prodn Supv
                position: 3, // MH
                // user_level_id: 4,
              },
              dataType: "json",
              success: function(JsonObject)
              {
                if(JsonObject['result'] == 1)
                  GetEmployeeDetails($('#txt_employee_id').val(), arrayProdnEmp, arrayProdnEmpName, modalCheckedByValues, modalCheckedByHiddenValues);
                else if(JsonObject['result'] == 0)
                  toastr.error('Scanned Employee ID is not Prodn Employee.');
                else
                  toastr.error(JsonObject['error_msg']);
              },
              error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
              }

            });            
          }
        }
    });

    function GetEmployeeDetails(employee_id, array_prodn, array_prodn_name, modalCheckedByValues, modalCheckedByHiddenValues){
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
            if(!array_prodn.includes(JsonObject['user_details'][0].id))
            {
              array_prodn.push(userId);
              array_prodn_name.push(userName);

              $('#'+modalCheckedByHiddenValues).val(userId.toString());
              $('#'+modalCheckedByValues).val(userName.toString());
              console.log('array_prodn_name: ',array_prodn_name);
              console.log('array_prodn: ',array_prodn);
            }
            else
            {
              toastr.error('Operator already added!');
            }


            /**
             * Old
             * 07-14-2022
             */
            // if(!array_prodn.includes(JsonObject['user_details'][0].id))
            // {
            //   array_prodn.push(JsonObject['user_details'][0].id);
            //   array_prodn_name.push(JsonObject['user_details'][0].name);

            //   $('#hidden_AKLdrawing_pn').val(JsonObject['user_details'][0].id.toString());
            //   $('#txt_AKLdrawing_pn2').val(JsonObject['user_details'][0].toString());
            // }
            // else
            // {
            //   toastr.error('Operator already added!');
            // }
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

    $('.btnPopLastProdn').click(function(){
      let dataRemoveCheckedBy = $(this).attr('data-remove-checked-by'); // Employee name
      let dataRemoveCheckedByIdVal = $('#'+dataRemoveCheckedBy).val(); // Employee Id Values
      let dataRemoveCheckedByIdHidden = $(this).attr('data-remove-checked-by-hidden'); // Employee Id
      let dataRemoveCheckedByIdHiddenVal = parseInt($('#'+dataRemoveCheckedByIdHidden).val()); // Employee Id Values
      console.log('dataRemoveCheckedBy: ', dataRemoveCheckedBy);
      console.log('dataRemoveCheckedByIdHidden: ', dataRemoveCheckedByIdHidden);
      console.log('dataRemoveCheckedByIdHiddenVal values: ', dataRemoveCheckedByIdHiddenVal);
      
      if(arrayProdnEmp.includes(dataRemoveCheckedByIdHiddenVal)){ // array ids vs hidden id
        
        arrayProdnEmp.splice(arrayProdnEmp.indexOf(dataRemoveCheckedByIdHiddenVal), 1);
        arrayProdnEmpName.splice(arrayProdnEmpName.indexOf(dataRemoveCheckedByIdVal), 1);

        $('#'+dataRemoveCheckedBy).val('');
        $('#'+dataRemoveCheckedByIdHidden).val('');
      }
      console.log('arrayProdnEmpName:', arrayProdnEmpName);
      console.log('arrayProdnEmp:', arrayProdnEmp);

      /**
       * Old
       * 07-14-2022
       */
      // $('#txt_AKLdrawing_pn').val(arrayProdnEmp.toString()); 
      // $('#txt_AKLdrawing_pn2').val(arrayProdnEmpName.toString());

    }); 




  });



  



  </script>
  @endsection
@endauth